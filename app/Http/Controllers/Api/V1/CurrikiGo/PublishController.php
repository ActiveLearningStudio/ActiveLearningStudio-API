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
use App\Http\Requests\CurrikiGo\PublishPlaylistRequest;

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
     *
     * @urlParam  project required The ID of the project
     * @urlParam  playlist required The ID of the playlist.
     * @bodyParam  setting_id int The id of the LMS setting.
     * @bodyParam  counter int The counter for uniqueness of the title
     * 
     * @param Project $project The project model object
     * @param Playlist $playlist The playlist model object
     * @param PublishPlaylistRequest $publishRequest The request object
     * @return Response
     */
    public function playlistToMoodle(Project $project, Playlist $playlist, PublishPlaylistRequest $publishRequest)
    {                
        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
            ], 400);
        }
        
        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('publish-to-lms', $project)) {
            $data = $publishRequest->validated();
            $lmsSetting = $this->lmsSettingRepository->find($data['setting_id']);
            $counter = (isset($data['counter']) ? intval($data['counter']) : 0);
            
            $moodle_playlist = new MoodlePlaylist($lmsSetting);
            $response = $moodle_playlist->send($playlist, ['counter' => intval($counter)]);

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
     * 
     * @param Project $project The project model object
     * @param Playlist $playlist The playlist model object
     * @param PublishPlaylistRequest $publishRequest The request object
     * @return Response
     */
    public function playlistToCanvas(Project $project, Playlist $playlist, PublishPlaylistRequest $publishRequest)
    {
        if ($playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or playlist Id.'],
            ], 400);
        }

        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('publish-to-lms', $project)) {
            // User can publish
            $data = $publishRequest->validated();
            $lmsSettings = $this->lmsSettingRepository->find($data['setting_id']);
            $canvasClient = new Client($lmsSettings);
            $canvasPlaylist = new CanvasPlaylist($canvasClient);
            $counter = (isset($data['counter']) ? intval($data['counter']) : 0);
            $outcome = $canvasPlaylist->send($playlist, ['counter' => $counter]);
            
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
