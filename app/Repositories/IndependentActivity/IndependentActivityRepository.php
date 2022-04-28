<?php

namespace App\Repositories\IndependentActivity;

use App\Models\IndependentActivity;
use App\Models\Organization;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Http\Resources\V1\SearchPostgreSqlResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use Illuminate\Support\Facades\DB;

class IndependentActivityRepository extends BaseRepository implements IndependentActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;
    private $client;

    /**
     * IndependentActivityRepository constructor.
     *
     * @param IndependentActivity $model
     * @param H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository
     */
    public function __construct(IndependentActivity $model, H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository)
    {
        parent::__construct($model);
        $this->client = new \GuzzleHttp\Client();
        $this->h5pElasticsearchFieldRepository = $h5pElasticsearchFieldRepository;
    }

    /**
     * Update model in storage
     *
     * @param array $attributes
     * @param $id
     * @return Model
     */
    public function update(array $attributes, $id)
    {
        $independentActivityObj = $this->model->find($id);

        if (
            isset($attributes['organization_visibility_type_id']) &&
            $independentActivityObj->organization_visibility_type_id !== (int)$attributes['organization_visibility_type_id']
        ) {
            $attributes['indexing'] = config('constants.indexing-requested');
            $attributes['status'] = config('constants.status-finished');

            if ((int)$attributes['organization_visibility_type_id'] === config('constants.private-organization-visibility-type-id')) {
                $attributes['status'] = config('constants.status-draft');
            }
        }

        return $this->model->where('id', $id)->update($attributes);
    }

    /**
     * Get the advance search request
     *
     * @param array $data
     * @param int $authUser
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function advanceSearchForm($data, $authUser = null)
    {
        $counts = [];
        $organizationParentChildrenIds = [];
        $queryParams['query_text'] = null;
        $queryFrom = 0;
        $querySize = 10;

        if (isset($data['searchType']) && $data['searchType'] === 'showcase_projects') {
            $organization = $data['orgObj'];
            $organizationParentChildrenIds = resolve(OrganizationRepositoryInterface::class)->getParentChildrenOrganizationIds($organization);
        }

        if ($authUser) {
            $query = 'SELECT * FROM advSearch(:user_id, :query_text)';

            $queryParams['user_id'] = $authUser;
        } else {
            $query = 'SELECT * FROM advSearch(:query_text)';
        }

        $countsQuery = 'SELECT entity, count(1) FROM (' . $query . ')sq GROUP BY entity';
        $queryWhere[] = "deleted_at IS NULL";
        $queryWhere[] = "(standalone_activity_user_id IS NULL OR standalone_activity_user_id = 0)";
        $modelMapping = ['projects' => 'Project', 'playlists' => 'Playlist', 'activities' => 'Activity', 'independent_activities' => 'Independent Activity'];

        if (isset($data['startDate']) && !empty($data['startDate'])) {
           $queryWhere[] = "created_at >= '" . $data['startDate'] . "'::date";
        }

        if (isset($data['endDate']) && !empty($data['endDate'])) {
            $queryWhere[] = "created_at <= '" . $data['endDate'] . "'::date";
        }

        if (isset($data['searchType']) && !empty($data['searchType'])) {
            $dataSearchType = $data['searchType'];
            if (
                $dataSearchType === 'my_projects'
                || $dataSearchType === 'org_projects_admin'
                || $dataSearchType === 'org_projects_non_admin'
            ) {
                if (isset($data['organizationIds']) && !empty($data['organizationIds'])) {
                    $dataOrganizationIds = implode(',', $data['organizationIds']);
                    $queryWhere[] = "org_id IN (" . $dataOrganizationIds . ")";
                }

                if ($dataSearchType === 'org_projects_non_admin') {
                    $queryWhere[] = "organization_visibility_type_id NOT IN (" . config('constants.private-organization-visibility-type-id') . ")";
                }
            } elseif ($dataSearchType === 'showcase_projects') {
                // Get all public items
                $organizationIdsShouldQueries[] = "organization_visibility_type_id IN (" . config('constants.public-organization-visibility-type-id') . ")";

                // Get all global items
                $dataOrganizationParentChildrenIds = implode(',', $organizationParentChildrenIds);
                $globalOrganizationIdsQueries[] = "org_id IN (" . $dataOrganizationParentChildrenIds . ")";
                $globalOrganizationIdsQueries[] = "organization_visibility_type_id IN (" . config('constants.global-organization-visibility-type-id') . ")";

                $globalOrganizationIdsQueries = implode(' AND ', $globalOrganizationIdsQueries);
                $organizationIdsShouldQueries[] = "(" . $globalOrganizationIdsQueries . ")";

                // Get all protected items
                $dataOrganizationIds = implode(',', $data['organizationIds']);
                $protectedOrganizationIdsQueries[] = "org_id IN (" . $dataOrganizationIds . ")";
                $protectedOrganizationIdsQueries[] = "organization_visibility_type_id IN (" . config('constants.protected-organization-visibility-type-id') . ")";

                $protectedOrganizationIdsQueries = implode(' AND ', $protectedOrganizationIdsQueries);
                $organizationIdsShouldQueries[] = "(" . $protectedOrganizationIdsQueries . ")";

                $organizationIdsShouldQueries = implode(' OR ', $organizationIdsShouldQueries);
                $queryWhere[] = "(" . $organizationIdsShouldQueries . ")";
            }
        } else {
            if (isset($data['organizationVisibilityTypeIds']) && !empty($data['organizationVisibilityTypeIds'])) {
                $dataOrganizationVisibilityTypeIds = implode(',', array_values(array_filter($data['organizationVisibilityTypeIds'])));
                if (in_array(null, $data['organizationVisibilityTypeIds'], true)) {
                    $organizationVisibilityTypeQueries[] = "organization_visibility_type_id IN (" . $dataOrganizationVisibilityTypeIds . ")";
                    $organizationVisibilityTypeQueries[] = "organization_visibility_type_id IS NULL";
                    $organizationVisibilityTypeQueries = implode(' OR ', $organizationVisibilityTypeQueries);
                    $queryWhere[] = "(" . $organizationVisibilityTypeQueries . ")";
                } else {
                    $queryWhere[] = "organization_visibility_type_id IN (" . $dataOrganizationVisibilityTypeIds . ")";
                }
            }
        }

        if (isset($data['subjectIds']) && !empty($data['subjectIds'])) {
            $dataSubjectIds = implode("','", $data['subjectIds']);
            $queryWhere[] = "subject_id IN ('" . $dataSubjectIds . "')";
        }

        if (isset($data['educationLevelIds']) && !empty($data['educationLevelIds'])) {
            $dataEducationLevelIds = implode("','", $data['educationLevelIds']);
            $queryWhere[] = "education_level_id IN ('" . $dataEducationLevelIds . "')";
        }

        if (isset($data['userIds']) && !empty($data['userIds'])) {
            $dataUserIds = implode("','", $data['userIds']);
            $queryWhere[] = "user_id IN (" . $dataUserIds . ")";
        }

        if (isset($data['author']) && !empty($data['author'])) {
            $queryWhereAuthor[] = "first_name LIKE '%" . $data['author'] . "%'";
            $queryWhereAuthor[] = "last_name LIKE '%" . $data['author'] . "%'";
            $queryWhereAuthor[] = "email LIKE '%" . $data['author'] . "%'";

            $queryWhereAuthor = implode(' OR ', $queryWhereAuthor);
            $queryWhere[] = "(" . $queryWhereAuthor . ")";
        }

        if (isset($data['h5pLibraries']) && !empty($data['h5pLibraries'])) {
            $dataH5pLibraries = implode("','", $data['h5pLibraries']);
            $queryWhere[] = "h5plib IN ('" . $dataH5pLibraries . "')";
        }

        if (isset($data['indexing']) && !empty($data['indexing'])) {
            $dataIndexingIds = implode(',', array_values(array_filter($data['indexing'])));
            if (in_array(null, $data['indexing'], true)) {
                $indexingQueries[] = "indexing IN (" . $dataIndexingIds . ")";
                $indexingQueries[] = "indexing IS NULL";
                $indexingQueries = implode(' OR ', $indexingQueries);
                $queryWhere[] = "(" . $indexingQueries . ")";
            } else {
                $queryWhere[] = "indexing IN (" . $dataIndexingIds . ")";
            }
        }

        if (isset($data['query']) && !empty($data['query'])) {
            $queryParams['query_text'] = $data['query'];
        }

        if (isset($data['negativeQuery']) && !empty($data['negativeQuery'])) {
            $queryWhere[] = "name NOT LIKE '%" . $data['negativeQuery'] . "%'";
            $queryWhere[] = "description NOT LIKE '%" . $data['negativeQuery'] . "%'";
        }

        if (isset($data['model']) && !empty($data['model'])) {
            $dataModel = $modelMapping[$data['model']];
            $queryWhere[] = "entity IN ('" . $dataModel . "')";
        }

        if (isset($data['from']) && !empty($data['from'])) {
            $queryFrom = $data['from'];
        }

        if (isset($data['size']) && !empty($data['size'])) {
            $querySize = $data['size'];
        }

        if (!empty($queryWhere)) {
            $queryWhereStr = " WHERE " . implode(' AND ', $queryWhere);
            $countQuery = $query;
            $query = $query . $queryWhereStr;

            if (isset($data['model']) && !empty($data['model'])) {
                unset($queryWhere[count($queryWhere) - 1]);
            }
            
            $countQueryWhereStr = " WHERE " . implode(' AND ', $queryWhere);
            $countQuery = $countQuery . $countQueryWhereStr;
            $countsQuery = 'SELECT entity, count(1) FROM (' . $countQuery . ')sq GROUP BY entity';
        }

        $query = $query . "LIMIT " . $querySize . " OFFSET " . $queryFrom;

        $results = DB::select($query, $queryParams);
        $countResults = DB::select($countsQuery, $queryParams);

        if (isset($countResults)) {
            foreach ($countResults as $countResult) {
                $modelMappingKey = array_search($countResult->entity, $modelMapping);
                $counts[$modelMappingKey] = $countResult->count;
            }
        }

        $counts['total'] = array_sum($counts);

        return (SearchPostgreSqlResource::collection($results))->additional(['meta' => $counts]);
    }

    /**
     * To clone Independent Activity
     * 
     * @param Organization $organization
     * @param IndependentActivity $independentActivity
     * @param string $token
     * @return int
     */
    public function clone(Organization $organization, IndependentActivity $independentActivity, $token)
    {
        $h5p_content = $independentActivity->h5p_content;
        if ($h5p_content) {
            $h5p_content = $h5p_content->replicate(); // replicate the all data of original activity h5pContent relation
            $h5p_content->user_id = get_user_id_by_token($token); // just update the user id which is performing the cloning
            $h5p_content->save(); // this will return true, then we can get id of h5pContent
        }
        $newH5pContent = $h5p_content->id ?? null;

        // copy the content data if exist
        $this->copy_content_data($independentActivity->h5p_content_id, $newH5pContent);

        // detect is it duplicate request or clone
        $isDuplicate = $independentActivity->organization_id === $organization->id;

        if ($isDuplicate) {
            $this->model->where('organization_id', $independentActivity->organization_id)->where('order', '>', $independentActivity->order)->increment('order', 1);
        }

        $new_thumb_url = clone_thumbnail($independentActivity->thumb_url, "activities");
        $independent_activity_data = [
            'title' => ($isDuplicate) ? $independentActivity->title . "-COPY" : $independentActivity->title,
            'type' => $independentActivity->type,
            'content' => $independentActivity->content,
            'order' => ($isDuplicate) ? $independentActivity->order + 1 : $this->getOrder($organization->id) + 1,
            'h5p_content_id' => $newH5pContent, // set if new h5pContent created
            'thumb_url' => $new_thumb_url,
            'subject_id' => $independentActivity->subject_id,
            'education_level_id' => $independentActivity->education_level_id,
            'shared' => $independentActivity->shared,
        ];
        $cloned_activity = $this->create($independent_activity_data);

        if ($cloned_activity && count($independentActivity->subjects) > 0) {
            $cloned_activity->subjects()->attach($independentActivity->subjects);
        }
        if ($cloned_activity && count($independentActivity->educationLevels) > 0) {
            $cloned_activity->educationLevels()->attach($independentActivity->educationLevels);
        }
        if ($cloned_activity && count($independentActivity->authorTags) > 0) {
            $cloned_activity->authorTags()->attach($independentActivity->authorTags);
        }

        return $cloned_activity['id'];
    }

    /**
     * Get latest order of independent activity for Organization
     * @param $organizationId
     * @return int
     */
    public function getOrder($organizationId)
    {
        return $this->model->where('organization_id', $organizationId)
            ->orderBy('order', 'desc')
            ->value('order') ?? 0;
    }

    /**
     * @param $oldContentID
     * @param $newContentID
     * @return false
     */
    protected function copy_content_data($oldContentID, $newContentID): bool
    {
        if (!$oldContentID || !$newContentID) {
            return false;
        }
        $contentDir = storage_path('app/public/h5p/content/');
        if (!file_exists($contentDir . $oldContentID)) {
            return false;
        }
        \File::copyDirectory($contentDir . $oldContentID, $contentDir . $newContentID);
        $this->chown_r($contentDir . $newContentID); // update content directory owner to default apache
        return true;
    }

    /**
     * Change owner of the directory
     * @param $path
     * @param string $user
     */
    protected function chown_r($path, $user = 'www-data'): void
    {
        $dir = new \DirectoryIterator($path);
        foreach ($dir as $item) {
            chown($item->getPathname(), $user);
            if ($item->isDir() && !$item->isDot()) {
                $this->chown_r($item->getPathname());
            }
        }
    }

    /**
     * Create model in storage
     *
     * @param $authenticatedUser
     * @param $suborganization
     * @param $data
     * @return Model
     */
    public function createIndependentActivity($authenticatedUser, $suborganization, $data)
    {
        $data['order'] = 0;
        $data['organization_id'] = $suborganization->id;

        $authenticatedUserOrgIndependentActivityIdsString = $this->getUserIndependentActivityIdsInOrganization($authenticatedUser, $suborganization);

        return DB::transaction(function () use ($authenticatedUser, $data, $authenticatedUserOrgIndependentActivityIdsString) {

            if (!empty($authenticatedUserOrgIndependentActivityIdsString)) {
                // update order's
                $query = 'UPDATE "independent_activities" SET "order" = "order" + 1 WHERE "id" IN (' . $authenticatedUserOrgIndependentActivityIdsString . ')';
                $affectedIndependentActivitiesCount = DB::update($query);
            }

            $attributes = Arr::except($data, ['subject_id', 'education_level_id', 'author_tag_id']);
            $independentActivity = $authenticatedUser->independentActivities()->create($attributes);

            if ($independentActivity) {
                if (isset($data['subject_id'])) {
                    $independentActivity->subjects()->attach($data['subject_id']);
                }
                if (isset($data['education_level_id'])) {
                    $independentActivity->educationLevels()->attach($data['education_level_id']);
                }
                if (isset($data['author_tag_id'])) {
                    $independentActivity->authorTags()->attach($data['author_tag_id']);
                }

                return $independentActivity;
            }
        });
    }

    /**
     * Get user independent activity ids in org
     *
     * @param $authenticatedUser
     * @param $organization
     * @return array
     */
    public function getUserIndependentActivityIdsInOrganization($authenticatedUser, $organization)
    {
        $authenticatedUserOrgIndependentActivityIds = $authenticatedUser
                                        ->independentActivities()
                                        ->where('organization_id', $organization->id)
                                        ->pluck('id')
                                        ->all();

        $authenticatedUserOrgIndependentActivityIdsString = implode(",", $authenticatedUserOrgIndependentActivityIds);

        return $authenticatedUserOrgIndependentActivityIdsString;
    }
}
