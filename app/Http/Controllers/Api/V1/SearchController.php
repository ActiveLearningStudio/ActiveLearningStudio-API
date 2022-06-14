<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SearchRequest;
use App\Http\Requests\V1\SearchIndependentActivityRequest;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Repositories\Organization\OrganizationRepositoryInterface;
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
    private $independentActivityRepository;
    private $organizationRepository;

    /**
     * SearchController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     * @param IndependentActivityRepositoryInterface $independentActivityRepository
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        IndependentActivityRepositoryInterface $independentActivityRepository,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        $this->activityRepository = $activityRepository;
        $this->independentActivityRepository = $independentActivityRepository;
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Deep Linking Search
     *
     * Search projects, playlists and activities for deep linking
     *
     * @queryParam organization_id required The Id of a organization Example: 1
     * @queryParam query Query to search. Example: test
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

        $organization = $this->organizationRepository->find($data['organization_id']);
        $this->authorize('view', $organization);

        $data['organizationIds'] = [$data['organization_id']];

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
     * @queryParam organization_id required The Id of a organization Example: 1
     * @queryParam query Query to search. Example: test
     * @queryParam negativeQuery Terms that should not exist. Example: badword
     * @queryParam userIds Array of user ids to match. Example: [1]
     * @queryParam startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam endDate End date for search by date range. Example: 2020-04-30 23:59:59
     * @queryParam subjectIds Array of subject ids to match. Example: [1]
     * @queryParam educationLevelIds Array of education level ids to match. Example: [1]
     * @queryParam authorTagsIds Array of author tags ids to match. Example: [1]
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

        $organization = $this->organizationRepository->find($data['organization_id']);

        $data['organizationIds'] = [$data['organization_id']];
        $data['orgObj'] = $organization;
        $data['indexing'] = [config('constants.indexing-approved')];

        if ($data['searchType'] === 'org_projects') {
            $data['searchType'] = 'org_projects_non_admin';
        }

        $results = $this->activityRepository->advanceSearchForm($data, auth()->user()->id);

        return $results;
    }

    /**
     * Dashboard search
     *
     * Dashboard search for projects, playlists and activities irrespective of indexing status
     *
     * @queryParam  organization_id required The Id of a organization Example: 1
     * @queryParam  query Query to search. Example: test
     * @queryParam  negativeQuery Terms that should not exist. Example: badword
     * @queryParam  indexing Indexing requested, approved or not approved. Example: [3]
     * @queryParam  startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam  endDate End date for search by date range. Example: 2020-04-30 23:59:59
     * @queryParam  subjectIds Array of subject ids to match. Example: [1]
     * @queryParam  educationLevelIds Array of education level ids to match. Example: [1]
     * @queryParam  authorTagsIds Array of author tags ids to match. Example: [1]
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

        $data['organizationIds'] = [$data['organization_id']];

        $results = $this->activityRepository->advanceSearchForm($data, auth()->user()->id);

        return $results;
    }

    /**
     * Independent Activities search
     *
     * Search for independent activities
     *
     * @queryParam searchType required It can be my_activities, showcase_activities, org_activities Example: my_activities
     * @queryParam indexing It can be one of the indexing options Example: null
     * @queryParam author The user name to filter by Example: Abby
     * @queryParam organization_id required The Id of a organization Example: 1
     * @queryParam query Query to search. Example: test
     * @queryParam negativeQuery Terms that should not exist. Example: badword
     * @queryParam userIds Array of user ids to match. Example: [1]
     * @queryParam startDate Start date for search by date range. Example: 2020-04-30 00:00:00
     * @queryParam endDate End date for search by date range. Example: 2020-04-30 23:59:59
     * @queryParam subjectIds Array of subject ids to match. Example: [1]
     * @queryParam educationLevelIds Array of education level ids to match. Example: [1]
     * @queryParam authorTagsIds Array of author tags ids to match. Example: [1]
     * @queryParam h5pLibraries Array of h5p libraries to match. Example: ['H5P.InteractiveVideo 1.21']
     * @queryParam sort Field to sort by. Example: created_at
     * @queryParam order Order to sort by. Example: desc
     * @queryParam from Index where the pagination start from. Example: 0
     * @queryParam size Number of records to return. Example: 10
     *
     * @responseFile responses/search/independent-activities.json
     *
     * @response  400 {
     *   "errors": {
     *     "userIds": [
     *       "The user Ids must be an array."
     *     ]
     *   }
     * }
     *
     * @param SearchIndependentActivityRequest $searchIndependentActivityRequest
     * @return Collection
     */
    public function independentActivities(SearchIndependentActivityRequest $searchIndependentActivityRequest)
    {
        $data = $searchIndependentActivityRequest->validated();

        $data['organizationIds'] = [$data['organization_id']];

        if ($data['searchType'] === 'org_activities') {
            $data['searchType'] = 'org_activities_non_admin';
            $data['indexing'] = [config('constants.indexing-approved')];
        } else if ($data['searchType'] === 'my_activities') {
            $data['userIds'] = [auth()->user()->id];
        } else {
            $data['indexing'] = [config('constants.indexing-approved')];
            $organization = $this->organizationRepository->find($data['organization_id']);
            $data['orgObj'] = $organization;
        }

        $results = $this->independentActivityRepository->advanceSearchForm($data, auth()->user()->id);

        return $results;
    }
}
