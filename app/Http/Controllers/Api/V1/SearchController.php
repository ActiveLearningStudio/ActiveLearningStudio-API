<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

/**
 * @authenticated
 *
 * @group  Search API
 *
 * APIs for search
 */
class SearchController extends Controller
{
    private $activityRepository;

    /**
     * SearchController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     */
    public function __construct(ActivityRepositoryInterface $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    /**
     * Deep Linking Search
     *
     * Search projects, playlists and activities for deep linking
     *
     * @queryParam  query required Query to search. Example: test
     * @queryParam  sort Field to sort by. Example: created_at
     * @queryParam  order Order to sort by. Example: desc
     * @queryParam  from Index where the pagination start from. Example: 0
     * @queryParam  size Number of records to return. Example: 10
     *
     * @response  {
     *     "projects": {
     *        "457": {
     *           "id": 457,
     *           "name": "Text Structure Lesson 2 Problem and Solution",
     *           "description": "Learning Objective\nThe learner will define and describe the main characteristics...",
     *           "thumb_url": "/storage/uploads/3zjZuLoQrRk0MZ5wP7UPyOut5zUybf3tW3a4Q2M1.png",
     *           "mongo_userid": "5ef5300e41668b53ea5ed1b3",
     *           "starter_project": false,
     *           "created_at": "2020-07-17T17:49:01.000000Z",
     *           "updated_at": "2020-08-06T13:28:07.000000Z",
     *           "deleted_at": null,
     *           "playlists": {
     *              "225": {
     *                 "id": 225,
     *                 "title": "Solving Ratio and Rate Problems",
     *                 "project_id": 64,
     *                 "order": null,
     *                 "mongo_projectid": "5f3ae92a924a1d5ddf44dd80",
     *                 "created_at": null,
     *                 "updated_at": null,
     *                 "deleted_at": null,
     *                 "activities": {
     *                    "993": {
     *                       "id": 993,
     *                       "playlist_id": 225,
     *                       "title": "",
     *                       "type": "h5p",
     *                       "content": "",
     *                       "h5p_content_id": 17474,
     *                       "thumb_url": "/storage/uploads/5f3aedeba24fb.jpeg",
     *                       "subject_id": null,
     *                       "education_level_id": null,
     *                       "shared": false,
     *                       "order": 11,
     *                       "mongo_playlistid": "5f3aeccd924a1d5ddf44ddd0",
     *                       "created_at": null,
     *                       "updated_at": null,
     *                       "deleted_at": null
     *                    }
     *                 }
     *              }
     *           }
     *        }
     *     }
     * }
     * @response  400 {
     *     "errors": {
     *         "query": [
     *             "The query field is required."
     *         ]
     *     }
     * }
     * @param SearchRequest $searchRequest
     * @return Response
     */
    public function search(SearchRequest $searchRequest)
    {
        $data = $searchRequest->validated();

        $projects = $this->activityRepository->searchForm($data);

        return response([
            'projects' => $projects,
        ], 200);
    }

    /**
     * Advance search
     *
     * Advance search for projects, playlists and activities
     *
     * @queryParam  query required Query to search. Example: test
     * @queryParam  negativeQuery Terms that should not exist. Example: badword
     * @queryParam  userIds Array of user ids to match. Example: [1]
     * @queryParam  model Index to filter by. Example: activities
     * @queryParam  sort Field to sort by. Example: created_at
     * @queryParam  order Order to sort by. Example: desc
     * @queryParam  from Index where the pagination start from. Example: 0
     * @queryParam  size Number of records to return. Example: 10
     *
     * @apiResourceCollection  App\Http\Resources\V1\SearchResource
     * @apiResourceModel  App\Models\Activity
     *
     * @response  {
     *     "data": [
     *         {
     *             "id": 457,
     *             "thumb_url": "/storage/uploads/3zjZuLoQrRk0MZ5wP7UPyOut5zUybf3tW3a4Q2M1.png",
     *             "title": "Text Structure Lesson 2 Problem and Solution",
     *             "description": "Learning Objective\nThe learner will define and describe the main characteristics...",
     *             "model": "Project",
     *             "user": {
     *                 "id": 1,
     *                 "name": "localuser",
     *                 "email": "localuser@local.com",
     *                 "email_verified_at": null,
     *                 "created_at": "2020-08-22T12:13:52.000000Z",
     *                 "updated_at": "2020-08-22T12:13:52.000000Z",
     *                 "first_name": "test",
     *                 "last_name": "test",
     *                 "organization_name": "organization_name",
     *                 "job_title": "job_title",
     *                 "address": null,
     *                 "phone_number": null,
     *                 "organization_type": null,
     *                 "website": null,
     *                 "deleted_at": null,
     *                 "role": null,
     *                 "gapi_access_token": null,
     *                 "pivot": {
     *                     "project_id": 457,
     *                     "user_id": 1,
     *                     "role": "teacher",
     *                     "created_at": "2020-08-25T09:35:35.000000Z",
     *                     "updated_at": "2020-08-25T09:35:35.000000Z"
     *                 }
     *             }
     *         },
     *         {
     *             "id": 1102,
     *             "title": "All About That Text",
     *             "model": "Playlist",
     *             "user": null
     *         }
     *     ],
     *     "meta": {
     *         "projects": 7,
     *         "playlists": 3,
     *         "activities": 2,
     *         "total": 12
     *     }
     * }
     * @response  400 {
     *     "errors": {
     *         "userIds": [
     *             "The user ids must be an array."
     *         ]
     *     }
     * }
     *
     * @param SearchRequest $searchRequest
     * @return Response
     */
    public function advance(SearchRequest $searchRequest)
    {
        $data = $searchRequest->validated();

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }
}
