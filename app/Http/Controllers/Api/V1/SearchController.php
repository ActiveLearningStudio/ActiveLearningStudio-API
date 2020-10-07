<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SearchRequest;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

/**
 * @group 13. Search
 *
 * APIs for search management
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
     * @queryParam query required Query to search. Example: test
     * @queryParam sort Field to sort by. Example: created_at
     * @queryParam order Order to sort by. Example: desc
     * @queryParam from Index where the pagination start from. Example: 0
     * @queryParam size Number of records to return. Example: 10
     *
     * @response  {
     *   "projects": {
     *     "457": {
     *       "id": 457,
     *       "name": "Text Structure Lesson 2 Problem and Solution",
     *       "description": "Learning Objective\nThe learner will define and describe the main characteristics...",
     *       "thumb_url": "/storage/projects/3zjZuLoQrRk0MZ5wP7UPyOut5zUybf3tW3a4Q2M1.png",
     *       "starter_project": false,
     *       "created_at": "2020-07-17T17:49:01.000000Z",
     *       "updated_at": "2020-08-06T13:28:07.000000Z",
     *       "deleted_at": null,
     *       "playlists": {
     *         "225": {
     *           "id": 225,
     *           "title": "Solving Ratio and Rate Problems",
     *           "project_id": 457,
     *           "order": null,
     *           "created_at": null,
     *           "updated_at": null,
     *           "deleted_at": null,
     *           "activities": {
     *             "993": {
     *               "id": 993,
     *               "playlist_id": 225,
     *               "title": "",
     *               "type": "h5p",
     *               "content": "",
     *               "h5p_content_id": 17474,
     *               "thumb_url": "/storage/activities/5f3aedeba24fb.jpeg",
     *               "subject_id": null,
     *               "education_level_id": null,
     *               "shared": false,
     *               "order": 11,
     *               "created_at": null,
     *               "updated_at": null,
     *               "deleted_at": null
     *             }
     *           }
     *         }
     *       }
     *     }
     *   }
     * }
     *
     * @response 400 {
     *   "errors": {
     *     "query": [
     *       "The query field is required."
     *     ]
     *   }
     * }
     *
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
     * Advance search for projects, playlists and activities having isPublic and elasticsearch set to true
     *
     * @queryParam query required Query to search. Example: test
     * @queryParam negativeQuery Terms that should not exist. Example: badword
     * @queryParam userIds Array of user ids to match. Example: [1]
     * @queryParam model Index to filter by. Example: activities
     * @queryParam sort Field to sort by. Example: created_at
     * @queryParam order Order to sort by. Example: desc
     * @queryParam from Index where the pagination start from. Example: 0
     * @queryParam size Number of records to return. Example: 10
     *
     * @response {
     *   "data": [
     *     {
     *       "id": 457,
     *       "thumb_url": "/storage/projects/3zjZuLoQrRk0MZ5wP7UPyOut5zUybf3tW3a4Q2M1.png",
     *       "title": "Text Structure Lesson 2 Problem and Solution",
     *       "description": "Learning Objective\nThe learner will define and describe the main characteristics...",
     *       "model": "Project",
     *       "user": {
     *         "id": 1,
     *         "email": "john.doe@currikistudio.org",
     *         "first_name": "John",
     *         "last_name": "Doe",
     *         "organization_name": "Curriki",
     *         "job_title": "Developer",
     *         "address": null,
     *         "phone_number": null,
     *         "organization_type": null,
     *         "website": null,
     *         "pivot": {
     *           "project_id": 457,
     *           "user_id": 1,
     *           "role": "owner"
     *         }
     *       }
     *     },
     *     {
     *       "id": 1102,
     *       "title": "All About That Text",
     *       "model": "Playlist",
     *       "user": null
     *     }
     *   ],
     *   "meta": {
     *     "projects": 7,
     *     "playlists": 3,
     *     "activities": 2,
     *     "total": 12
     *   }
     * }
     *
     * @response  400 {
     *   "errors": {
     *     "userIds": [
     *       "The user Ids must be an array."
     *     ]
     *   }
     * }
     *
     * @param SearchRequest $searchRequest
     * @return Collection
     */
    public function advance(SearchRequest $searchRequest)
    {
        $data = $searchRequest->validated();
        $data['isPublic'] = true;
        $data['elasticsearch'] = true;

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }

    /**
     * Dashboard search
     *
     * Dashboard search for projects, playlists and activities irrespective of isPublic and elasticsearch status
     *
     * @queryParam  query required Query to search. Example: test
     * @queryParam  negativeQuery Terms that should not exist. Example: badword
     * @queryParam  isPublic Public access enabled or disabled. Example: true
     * @queryParam  elasticsearch Elasticsearch enabled or disabled. Example: false
     * @queryParam  startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam  endDate End date for search by date range. Example: 2020-04-30 23:59:59
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
     *                     "role": "owner",
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
     * @return Collection
     */
    public function dashboard(SearchRequest $searchRequest)
    {
        $data = $searchRequest->validated();
        $data['userIds'] = [auth()->user()->id];

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }
}
