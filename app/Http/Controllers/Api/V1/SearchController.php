<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SearchRequest;
use App\Http\Requests\V1\SearchPublicRequest;
use App\Repositories\Activity\ActivityRepositoryInterface;
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
    private $organizationRepository;

    /**
     * SearchController constructor.
     *
     * @param ActivityRepositoryInterface $activityRepository
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(
        ActivityRepositoryInterface $activityRepository,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        $this->activityRepository = $activityRepository;
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

        $organization = $this->organizationRepository->find($data['organization_id']);
        $this->authorize('advanceSearch', $organization);

        $data['organizationIds'] = [$data['organization_id']];
        $data['orgObj'] = $organization;

        if ($data['searchType'] === 'showcase_projects') {
            $data['indexing'] = [config('constants.indexing-approved')];
        } elseif ($data['searchType'] === 'org_projects') {
            if (!auth()->user()->hasPermissionTo('organization:view', $organization)) {
                $data['searchType'] = 'org_projects_non_admin';
            } else {
                $data['searchType'] = 'org_projects_admin';
            }
        }

        $results = $this->activityRepository->advanceSearchForm($data);

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

        $organization = $this->organizationRepository->find($data['organization_id']);
        $this->authorize('dashboardSearch', $organization);

        $data['userIds'] = [auth()->user()->id];

        $data['organizationIds'] = [$data['organization_id']];

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }

    /**
     * Public search
     *
     * Search for projects, playlists and activities having indexing approved and public visibility
     *
     * @queryParam organization_id The Id of a organization Example: 1
     * @queryParam query Query to search. Example: test
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
     * @responseFile responses/search/public.json
     *
     * @response  400 {
     *   "errors": {
     *     "userIds": [
     *       "The user Ids must be an array."
     *     ]
     *   }
     * }
     *
     * @param SearchPublicRequest $searchPublicRequest
     * @return Collection
     */
    public function public(SearchPublicRequest $searchPublicRequest)
    {
        $data = $searchPublicRequest->validated();

        if (isset($data['organization_id'])) {
            $data['organizationIds'] = [$data['organization_id']];
        }

        $data['organizationVisibilityTypeIds'] = [null, config('constants.public-organization-visibility-type-id')];
        $data['indexing'] = [config('constants.indexing-approved')];

        $results = $this->activityRepository->advanceSearchForm($data);

        return $results;
    }
}
