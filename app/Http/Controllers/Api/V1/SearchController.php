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
     * @responseFile responses/search/search.json
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
     * Advance search for projects, playlists and activities having indexing approved
     *
     * @queryParam query required Query to search. Example: test
     * @queryParam negativeQuery Terms that should not exist. Example: badword
     * @queryParam userIds Array of user ids to match. Example: [1]
     * @queryParam startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam endDate End date for search by date range. Example: 2020-04-30 23:59:59
     * @queryParam subjectIds Array of subject ids to match. Example: ['ComputerScience']
     * @queryParam educationLevelIds Array of education level ids to match. Example: ['Preschool (Ages 0-4)']
     * @queryParam h5pLibraries Array of h5p libraries to match. Example: ['H5P.InteractiveVideo 1.21']
     * @queryParam model Index to filter by. Example: activities
     * @queryParam sort Field to sort by. Example: created_at
     * @queryParam order Order to sort by. Example: desc
     * @queryParam from Index where the pagination start from. Example: 0
     * @queryParam size Number of records to return. Example: 10
     *
     * @responseFile responses/search/advance.json
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
        $data['indexing'] = [3];

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }

    /**
     * Dashboard search
     *
     * Dashboard search for projects, playlists and activities irrespective of indexing status
     *
     * @queryParam  query required Query to search. Example: test
     * @queryParam  negativeQuery Terms that should not exist. Example: badword
     * @queryParam  indexing Indexing requested, approved or not approved. Example: [3]
     * @queryParam  startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam  endDate End date for search by date range. Example: 2020-04-30 23:59:59
     * @queryParam  subjectIds Array of subject ids to match. Example: ['ComputerScience']
     * @queryParam  educationLevelIds Array of education level ids to match. Example: ['Preschool (Ages 0-4)']
     * @queryParam  h5pLibraries Array of h5p libraries to match. Example: ['H5P.InteractiveVideo 1.21']
     * @queryParam  model Index to filter by. Example: activities
     * @queryParam  sort Field to sort by. Example: created_at
     * @queryParam  order Order to sort by. Example: desc
     * @queryParam  from Index where the pagination start from. Example: 0
     * @queryParam  size Number of records to return. Example: 10
     *
     * @apiResourceCollection  App\Http\Resources\V1\SearchResource
     * @apiResourceModel  App\Models\Activity
     *
     * @responseFile responses/search/dashboard.json
     *
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
