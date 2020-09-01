<?php

/**
 * This file contains handlers for LMS publishing.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\CurrikiGo\Moodle\Playlist as MoodlePlaylist;
use App\CurrikiGo\Canvas\Playlist as CanvasPlaylist;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\CurrikiGo\Canvas\Client;
use Validator;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group  CurrikiGo
 * @authenticated
 * 
 * APIs for publishing playlists to other LMSs
 */
class PublishController extends Controller
{
    /**
     * $lmsSettingRepository
     */
    private $lmsSettingRepository;

    /**
     * PublishController constructor.
     *
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
    }

    /**
     * Publish a Playlist to Moodle
     */
    public function playlistToMoodle(Request $request)
    {                
        $validator = Validator::make($request->all(), ['setting_id' => 'required', 'playlist_id'=> 'required']);

        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['error' => $messages]);
        }
        
        $moodle_playlist = new MoodlePlaylist($request->setting_id);
        $counter = $request->counter ? $request->counter : 0;
        $response = $moodle_playlist->send(['playlist_id' => $request->playlist_id, 'counter' => intval($counter)]);

        $code = $response->getStatusCode();
        if ($code == 200) {
            $outcome = $response->getBody()->getContents();
            return response([
                'data' => json_decode($outcome),
            ], 200);
        } else {
            return response([
                'errors' => ['Error sending playlists to Moodle'],
            ], 500);
        }
    }

    /**
     * Publish a Playlist to Canvas
     *
     * @urlParam  project required The ID of the project
     * @urlParam  playlist required The ID of the playlist.
     * @bodyParam  setting_id int The id of the LMS setting.
     * @bodyParam  counter int The counter for uniqueness of the title
     * 
     * @responseFile  responses/playlisttocanvas.post.json
     * @response  400 {
     *  "errors": "Invalid project or playlist Id."
     * }
     * @response  403 {
     *  "errors": "You are not authorized to perform this action."
     * }
     * @response  500 {
     *  "errors": "Error sending playlists to canvas."
     * }
     */
    public function playlistToCanvas(Project $project, Playlist $playlist, Request $request)
    {
        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
            ], 400);
        }

        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('publish-to-lms', $project)) {
            // User can publish
            $validator = Validator::make($request->all(), ['setting_id' => 'required']);
            
            if ($validator->fails()) {
                $messages = $validator->messages();
                return response([
                    'errors' => [$messages],
                ], 400);
            }

            $lmsSettings = $this->lmsSettingRepository->find($request->setting_id);
            $canvasClient = new Client($lmsSettings);
            $canvasPlaylist = new CanvasPlaylist($canvasClient);
            $counter = $request->counter ? $request->counter : 0;
            $outcome = $canvasPlaylist->send($playlist, ['counter' => intval($counter)]);
            
            if ($outcome) {            
                return response([
                    'playlist' => $outcome,
                ], 200);
            } else {
                return response([
                    'errors' => ['Error sending playlists to Canvas.'],
                ], 500);
            }
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }
}
