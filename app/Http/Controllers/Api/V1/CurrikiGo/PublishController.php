<?php

/**
 * This file contains handlers for LMS publishing.
 */

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Playlist as CanvasPlaylist;
use App\CurrikiGo\Moodle\Playlist as MoodlePlaylist;
use App\CurrikiGo\SafariMontage\EasyUpload as SafariMontageEasyUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\CurrikiGo\PublishPlaylistRequest;
use App\Http\Requests\V1\CurrikiGo\PublishActivityRequest;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepository;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\CurrikiGo\Canvas\Course as CanvasCourse;
use App\Http\Requests\V1\CurrikiGo\CreateAssignmentRequest;
use App\Http\Requests\V1\CurrikiGo\PublishMoodlePlaylistRequest;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

/**
 * @group 10. CurrikiGo
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
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, LMSIntegrationServiceInterface $lms)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->lms = $lms;
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
     * @param PublishMoodlePlaylistRequest $publishRequest
     * @param Project $project
     * @param Playlist $playlist
     * @param GoogleClassroomRepositoryInterface $gcrRepo
     * @return Response
     */
    // TODO: need to add 200 response
    public function playlistToMoodle(PublishMoodlePlaylistRequest $publishRequest, Project $project, Playlist $playlist, GoogleClassroomRepositoryInterface $gcrRepo)
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
            $response = $moodle_playlist->send($playlist, ['counter' => intval($counter)], $gcrRepo);

            $code = $response->getStatusCode();
            if ($code == 200) {
                return response([
                    'data' => json_decode($response->getBody()),
                ], 200);
            }

            return response([
                'errors' => ['Failed to send playlist to Moodle.'],
            ], 400);
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
     * @param GoogleClassroomRepositoryInterface $gcrRepo
     * @return Response
     */
    public function playlistToCanvas(Project $project, Playlist $playlist, PublishPlaylistRequest $publishRequest, GoogleClassroomRepositoryInterface $gcrRepo)
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
            $outcome = $canvasPlaylist->send($playlist, ['counter' => $counter], $publishRequest->creation_type, $publishRequest->canvas_course_id, $gcrRepo);

            if ($outcome) {
                return response([
                    'playlist' => $outcome,
                ], 200);
            }
            elseif ($outcome == false) {
                return response([
                    'errors' => ['Something went wrong while publishing.'],
                ], 400);
            }
            else {
                return response([
                    'errors' => ['Failed to send playlist to canvas.'],
                ], 500);
            }
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    // The idea of this function is to publish to any LMS
    // The actual publishing will be handled by a plugin in the app/CurrikiGo/LMS folder
    public function playlistToGeneric($lms, Project $project, Playlist $playlist, PublishPlaylistRequest $publishRequest)
    {
        return $this->lms->publishProject($lms, $project, LmsSetting::find($publishRequest->setting_id));
    }

    /**
     * Publish Activity to Safari Montage.
     *
     * Publish the specified playlist to Safari Montage.
     *
     * @urlParam project required The Id of the project Example: 1
     * @urlParam playlist required The Id of the playlist Example: 1
     * @urlParam activity required The Id of the activity Example: 1
     * @bodyParam setting_id int The Id of the LMS setting Example: 1
     * @bodyParam counter int The counter for uniqueness of the title Example: 1
     *
     * @responseFile responses/curriki-go/playlist-to-safari.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or activity Id."
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
     *     "Failed to send activity to safari."
     *   ]
     * }
     *
     * @param Project $project
     * @param Playlist $playlist
     * @param Activity $activity
     * @param PublishActivityRequest $publishRequest
     * @return Response
     */
    public function activityToSafariMontage(Project $project, Playlist $playlist, Activity $activity, PublishActivityRequest $publishRequest)
    {
        if ($activity->playlist->project_id !== $project->id) {
            return response([
                'errors' => ['Invalid project or activity Id.'],
            ], 400);
        }

        $authUser = auth()->user();
        if (Gate::forUser($authUser)->allows('publish-to-lms', $project)) {
            // User can publish
            $data = $publishRequest->validated();
            $lmsSettings = $this->lmsSettingRepository->find($data['setting_id']);

            $easyUpload = new SafariMontageEasyUpload($lmsSettings);
            $counter = (isset($data['counter']) ? intval($data['counter']) : 0);
            $outcome = $easyUpload->uploadActivity($activity, ['counter' => $counter]);

            if ($outcome) {
                return response([
                    'launch' => $outcome,
                ], 200);
            } else {
                return response([
                    'errors' => ['Failed to send playlist to Safari Montage.'],
                ], 500);
            }
        }

        return response([
            'errors' => ['You are not authorized to perform this action.'],
        ], 403);
    }

    /**
     * Publish Activity to Canvas.
     *
     * Publish the specified playlist to Canvas.
     *
     * @urlParam project required The Id of the project Example: 1
     * @urlParam playlist required The Id of the playlist Example: 1
     * @urlParam activity required The Id of the activity Example: 1
     * @bodyParam setting_id int The Id of the LMS setting Example: 1
     * @bodyParam counter int The counter for uniqueness of the title Example: 1
     *
     * @responseFile responses/curriki-go/assignment-to-canvas.json
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid project or activity Id."
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
     *     "Failed to send activity to Canvas."
     *   ]
     * }
     *
     * @param courseId
     * @param CreateAssignmentRequest $publishRequest
     * @return Response
     */
    public function activityToCanvas($courseId, CreateAssignmentRequest $publishRequest, GoogleClassroomRepositoryInterface $googleClassroomRepository)
    {
        $project = Activity::find($publishRequest->curriki_activity_id)->playlist->project;
        if (Gate::forUser(auth()->user())->allows('publish-to-lms', $project)) {
            // User can publish
            $lmsSettings = $this->lmsSettingRepository->find($publishRequest['setting_id']);
            $canvasClient = new Client($lmsSettings);
            $easyUpload = new CanvasCourse($canvasClient);

            $outcome = $easyUpload->createActivity($courseId, $publishRequest->assignment_group_id, $publishRequest->assignment_name, $publishRequest->curriki_activity_id);
            if(!is_int($outcome)){
                $teacherInfo = new \stdClass();
                $teacherInfo->user_id = null;
                $teacherInfo->id = $courseId;
                $teacherInfo->name = $outcome->name;
                $teacherInfo->alternateLink = $lmsSettings->lms_url . '/' . $courseId;
                $teacherInfo->alternateLink = substr($teacherInfo->alternateLink, 8);
                $teacherInfo->curriki_teacher_email = null;
                $teacherInfo->curriki_teacher_org = $lmsSettings->organization_id;
                $googleClassroomData = $googleClassroomRepository->saveCourseShareToGcClass($teacherInfo);
            }

            if (!is_int($outcome)) {
                return response([
                    'Activity' => $outcome,
                ], 200);
            } else if(($outcome === 401 || $outcome === 403)) {
                return response([
                    'response_code' => $outcome,
                    'response_message' => 'Canvas token is invalid, expired or missing permission to create a course',
                    'data' => $outcome,
                ], 500);
            }
        }

        return response([
            'errors' => ['Something went wrong with the API.'],
        ], 403);
    }

}
