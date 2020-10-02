<?php

/**
 * This file contains handlers for LMS publishing.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Playlist as CanvasPlaylist;
use App\CurrikiGo\Moodle\Playlist as MoodlePlaylist;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\PublishPlaylistRequest;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group 8. CurrikiGo
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
     * Publish Playlist to Moodle
     *
     * Publish the specified playlist to Moodle.
     *
     * @urlParam project required The Id of the project Example: 1
     * @urlParam playlist required The Id of the playlist Example: 1
     * @bodyParam setting_id int The Id of the LMS setting Example: 1
     * @bodyParam counter int The counter for uniqueness of the title Example: 1
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or playlist Id."
     *   ]
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You are not authorized to perform this action."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to send playlist to Moodle."
     *   ]
     * }
     *
     * @param PublishPlaylistRequest $publishRequest
     * @param Project $project
     * @param Playlist $playlist
     * @return Response
     */
    // TODO: need to add 200 response
    public function playlistToMoodle(PublishPlaylistRequest $publishRequest, Project $project, Playlist $playlist)
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
            }

            return response([
                'errors' => ['Failed to send playlist to Moodle.'],
            ], 500);
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    /**
     * Publish Playlist to Canvas
     *
     * Publish the specified playlist to Canvas.
     *
     * @urlParam project required The Id of the project Example: 1
     * @urlParam playlist required The Id of the playlist Example: 1
     * @bodyParam setting_id int The Id of the LMS setting Example: 1
     * @bodyParam counter int The counter for uniqueness of the title Example: 1
     *
     * @responseFile responses/curriki-go/playlist-to-canvas.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or playlist Id."
     *   ]
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You are not authorized to perform this action."
     *   ]
     * }
     *
     * @response  500 {
     *   "errors": [
     *     "Failed to send playlist to canvas."
     *   ]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @param PublishPlaylistRequest $publishRequest
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
                    'errors' => ['Failed to send playlist to canvas.'],
                ], 500);
            }
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }
}
