<?php

namespace App\Repositories\IndependentActivity;

use App\Exceptions\GeneralException;
use App\Models\IndependentActivity;
use App\Models\Organization;
use App\Models\Playlist;
use App\Models\Activity;
use App\Models\Subject;
use App\Models\EducationLevel;
use App\Models\AuthorTag;
use App\Repositories\IndependentActivity\IndependentActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\EducationLevel\EducationLevelRepositoryInterface;
use App\Http\Resources\V1\SearchPostgreSqlResource;
use App\Http\Resources\V1\SearchIndependentActivityResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class IndependentActivityRepository extends BaseRepository implements IndependentActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;
    private $subjectRepository;
    private $educationLevelRepository;
    private $client;

    /**
     * IndependentActivityRepository constructor.
     *
     * @param IndependentActivity $model
     * @param H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository
     * @param SubjectRepositoryInterface $subjectRepository
     * @param EducationLevelRepositoryInterface $educationLevelRepository
     */
    public function __construct(
        IndependentActivity $model,
        H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository,
        SubjectRepositoryInterface $subjectRepository,
        EducationLevelRepositoryInterface $educationLevelRepository
    )
    {
        parent::__construct($model);
        $this->client = new \GuzzleHttp\Client();
        $this->h5pElasticsearchFieldRepository = $h5pElasticsearchFieldRepository;
        $this->subjectRepository = $subjectRepository;
        $this->educationLevelRepository = $educationLevelRepository;
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
        $queryParams['query_text'] = '';
        $queryParams['query_subject'] = '';
        $queryParams['query_education'] = '';
        $queryParams['query_tags'] = '';
        $queryParams['query_h5p'] = '';
        $queryFrom = 0;
        $querySize = 10;

        if (isset($data['searchType']) && $data['searchType'] === 'showcase_activities') {
            $organization = $data['orgObj'];
            $organizationParentChildrenIds = resolve(OrganizationRepositoryInterface::class)->getParentChildrenOrganizationIds($organization);
        }

        if ($authUser) {
            $query = 'SELECT * FROM advindependentactivitysearch(:query_text, :query_subject, :query_education, :query_tags, :query_h5p)';
            $countsQuery = 'SELECT COUNT(*) AS total FROM advindependentactivitysearch(:query_text, :query_subject, :query_education, :query_tags, :query_h5p)';
        } else {
            $query = 'SELECT * FROM advindependentactivitysearch(:query_text, :query_subject, :query_education, :query_tags, :query_h5p)';
            $countsQuery = 'SELECT COUNT(*) AS total FROM advindependentactivitysearch(:query_text, :query_subject, :query_education, :query_tags, :query_h5p)';
        }

        $queryWhere[] = "deleted_at IS NULL";

        if (isset($data['startDate']) && !empty($data['startDate'])) {
           $queryWhere[] = "created_at >= '" . $data['startDate'] . "'::date";
        }

        if (isset($data['endDate']) && !empty($data['endDate'])) {
            $queryWhere[] = "created_at <= '" . $data['endDate'] . "'::date";
        }

        if (isset($data['searchType']) && !empty($data['searchType'])) {
            $dataSearchType = $data['searchType'];
            if (
                $dataSearchType === 'my_activities'
                || $dataSearchType === 'org_activities_admin'
                || $dataSearchType === 'org_activities_non_admin'
            ) {
                if (isset($data['organizationIds']) && !empty($data['organizationIds'])) {
                    $dataOrganizationIds = implode(',', $data['organizationIds']);
                    $queryWhere[] = "org_id IN (" . $dataOrganizationIds . ")";
                }

                if ($dataSearchType === 'org_activities_non_admin') {
                    $queryWhere[] = "organization_visibility_type_id NOT IN (" . config('constants.private-organization-visibility-type-id') . ")";
                }
            } elseif ($dataSearchType === 'showcase_activities') {
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
            $subjectIdsWithMatchingName = $this->subjectRepository->getSubjectIdsWithMatchingName($data['subjectIds']);
            $dataSubjectIds = implode(",", $subjectIdsWithMatchingName);
            $queryParams['query_subject'] = "(" . $dataSubjectIds . ")";
        }

        if (isset($data['educationLevelIds']) && !empty($data['educationLevelIds'])) {
            $educationLevelIdsWithMatchingName = $this->educationLevelRepository->getEducationLevelIdsWithMatchingName($data['educationLevelIds']);
            $dataEducationLevelIds = implode(",", $educationLevelIdsWithMatchingName);
            $queryParams['query_education'] = "(" . $dataEducationLevelIds . ")";
        }

        if (isset($data['authorTagsIds']) && !empty($data['authorTagsIds'])) {
            $dataAuthorTagsIds = implode(",", $data['authorTagsIds']);
            $queryParams['query_tags'] = "(" . $dataAuthorTagsIds . ")";
        }

        if (isset($data['userIds']) && !empty($data['userIds'])) {
            $dataUserIds = implode("','", $data['userIds']);
            $queryWhere[] = "user_id IN ('" . $dataUserIds . "')";
        }

        if (isset($data['author']) && !empty($data['author'])) {
            $queryWhereAuthor[] = "first_name LIKE '%" . $data['author'] . "%'";
            $queryWhereAuthor[] = "last_name LIKE '%" . $data['author'] . "%'";
            $queryWhereAuthor[] = "email LIKE '%" . $data['author'] . "%'";

            $queryWhereAuthor = implode(' OR ', $queryWhereAuthor);
            $queryWhere[] = "(" . $queryWhereAuthor . ")";
        }

        if (isset($data['h5pLibraries']) && !empty($data['h5pLibraries'])) {
            $dataH5pLibraries = implode('","', $data['h5pLibraries']);
            $queryParams['query_h5p'] = '("' . $dataH5pLibraries . '")';
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
            $queryWhere[] = "lower(name) NOT LIKE lower('%" . $data['negativeQuery'] . "%')";

            $descriptionQuery = "(";
            $descriptionQuery .= "lower(description) NOT LIKE lower('%" . $data['negativeQuery'] . "%')";
            $descriptionQuery .= " OR description IS NULL";
            $descriptionQuery .= ")";
            $queryWhere[] = $descriptionQuery;
        }

        if (isset($data['from']) && !empty($data['from'])) {
            $queryFrom = $data['from'];
        }

        if (isset($data['size']) && !empty($data['size'])) {
            $querySize = $data['size'];
        }

        if (!empty($queryWhere)) {
            $queryWhereStr = " WHERE " . implode(' AND ', $queryWhere);
            $query = $query . $queryWhereStr;
            $countsQuery = $countsQuery . $queryWhereStr;
        }

        $query = $query . "LIMIT " . $querySize . " OFFSET " . $queryFrom;

        $results = DB::select($query, $queryParams);
        $countResults = DB::select($countsQuery, $queryParams);

        $meta['total'] = $countResults[0]->total;

        return (SearchIndependentActivityResource::collection($results))->additional(['meta' => $meta]);
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

        $new_thumb_url = clone_thumbnail($independentActivity->thumb_url, "independent-activities");
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
            'user_id' => get_user_id_by_token($token),
            'organization_id' => $organization->id,
            'organization_visibility_type_id' => config('constants.private-organization-visibility-type-id'),
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

    /**
     * @param $data
     * @param $suborganization
     * @param $authUser
     * @return mixed
     */
    public function getAuthUserIndependentActivities($data, $suborganization, $authUser)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $query = $this->model;
        $q = $data['query'] ?? null;

        // if simple request for getting independent activity listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('title', 'iLIKE', '%' .$q. '%')
                    ->orWhereHas('user', function ($qry) use ($q) {
                        $qry->where('email', 'iLIKE', '%' .$q. '%');
                    });
            });
        }

        // if all indexed independent activities requested
        if (isset($data['indexing']) && $data['indexing'] === '0') {
            $query = $query->whereIn('indexing', [1, 2, 3]);
        }

        // if specific index independent activities requested
        if (isset($data['indexing']) && $data['indexing'] !== '0') {
            $query = $query->where('indexing', $data['indexing']);
        }

        // filter by author
        if (isset($data['author_id'])) {
            $query = $query->where(function($qry) use ($data) {
                        $qry->WhereHas('user', function ($qry) use ($data) {
                            $qry->where('id', $data['author_id']);
                        });
                     });
        }

        // filter by shared status
        if (isset($data['shared'])) {
            $query = $query->where('shared', $data['shared']);
        }

        // filter by date created
        if (isset($data['created_from']) && isset($data['created_to'])) {
            $query = $query->whereBetween('created_at', [$data['created_from'], $data['created_to']]);
        }

        if (isset($data['created_from']) && !isset($data['created_to'])) {
            $query = $query->whereDate('created_at', '>=', $data['created_from']);
        }

        if (isset($data['created_to']) && !isset($data['created_from'])) {
            $query = $query->whereDate('created_to', '<=', $data['created_to']);
        }

        // filter by date updated
        if (isset($data['updated_from']) && isset($data['updated_to'])) {
            $query = $query->whereBetween('updated_at', [$data['updated_from'], $data['updated_to']]);
        }

        if (isset($data['updated_from']) && !isset($data['updated_to'])) {
            $query = $query->whereDate('updated_at', '>=', $data['updated_from']);
        }

        if (isset($data['updated_to']) && !isset($data['updated_from'])) {
            $query = $query->whereDate('updated_at', '<=', $data['updated_to']);
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->where('organization_id', $suborganization->id)->where('user_id', $authUser->id)->paginate($perPage)->withQueryString();
    }

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        $query = $this->model;
        $q = $data['query'] ?? null;

        // if simple request for getting independent activity listing with search
        if ($q) {
            $query = $query->where(function($qry) use ($q) {
                $qry->where('title', 'iLIKE', '%' .$q. '%')
                    ->orWhereHas('user', function ($qry) use ($q) {
                        $qry->where('email', 'iLIKE', '%' .$q. '%');
                    });
            });
        }

        // if all indexed independent activities requested
        if (isset($data['indexing']) && $data['indexing'] === '0') {
            $query = $query->whereIn('indexing', [1, 2, 3]);
        }

        // if specific index independent activities requested
        if (isset($data['indexing']) && $data['indexing'] !== '0') {
            $query = $query->where('indexing', $data['indexing']);
        }

        // filter by author
        if (isset($data['author_id'])) {
            $query = $query->where(function($qry) use ($data) {
                        $qry->WhereHas('user', function ($qry) use ($data) {
                            $qry->where('id', $data['author_id']);
                        });
                     });
        }

        // filter by shared status
        if (isset($data['shared'])) {
            $query = $query->where('shared', $data['shared']);
        }

        // filter by date created
        if (isset($data['created_from']) && isset($data['created_to'])) {
            $query = $query->whereBetween('created_at', [$data['created_from'], $data['created_to']]);
        }

        if (isset($data['created_from']) && !isset($data['created_to'])) {
            $query = $query->whereDate('created_at', '>=', $data['created_from']);
        }

        if (isset($data['created_to']) && !isset($data['created_from'])) {
            $query = $query->whereDate('created_to', '<=', $data['created_to']);
        }

        // filter by date updated
        if (isset($data['updated_from']) && isset($data['updated_to'])) {
            $query = $query->whereBetween('updated_at', [$data['updated_from'], $data['updated_to']]);
        }

        if (isset($data['updated_from']) && !isset($data['updated_to'])) {
            $query = $query->whereDate('updated_at', '>=', $data['updated_from']);
        }

        if (isset($data['updated_to']) && !isset($data['updated_from'])) {
            $query = $query->whereDate('updated_at', '<=', $data['updated_to']);
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->withQueryString();
    }

     /**
     * To export project and associated playlists
     *
     * @param $authUser
     * @param IndependentActivity $independent_activity
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function exportIndependentActivity($authUser, IndependentActivity $independent_activity)
    {
        $zip = new ZipArchive;

        $activity_dir_name = 'independent_activity-'.uniqid();

        $activityTitle = str_replace('/', '-', $independent_activity->title);

        $activity_json_file = '/exports/' . $activity_dir_name .  '/activity.json';
        Storage::disk('public')->put($activity_json_file, $independent_activity);

        // Export Subject 
        $activitySubjectJsonFile = '/exports/' . $activity_dir_name . '/activity_subject.json';
        
        Storage::disk('public')->put($activitySubjectJsonFile, $independent_activity->subjects);

        // Export Education level

        $activityEducationLevelJsonFile = '/exports/' . $activity_dir_name . '/activity_education_level.json';
        
        Storage::disk('public')->put($activityEducationLevelJsonFile, $independent_activity->educationLevels);

        // Export Author

        $activityAuthorTagJsonFile = '/exports/' . $activity_dir_name . '/activity_author_tag.json';
        
        Storage::disk('public')->put($activityAuthorTagJsonFile, $independent_activity->authorTags);

        $decoded_content = json_decode($independent_activity->h5p_content,true);

        $decoded_content['library_title'] = DB::table('h5p_libraries')
                                                            ->where('id', $decoded_content['library_id'])->value('name');
        $decoded_content['library_major_version'] = DB::table('h5p_libraries')
                                                                ->where('id', $decoded_content['library_id'])
                                                                ->value('major_version');
        $decoded_content['library_minor_version'] = DB::table('h5p_libraries')
                                                                ->where('id', $decoded_content['library_id'])
                                                                ->value('minor_version');

        $content_json_file = '/exports/'.$activity_dir_name . '/' . $independent_activity->h5p_content_id . '.json';
        Storage::disk('public')->put($content_json_file, json_encode($decoded_content));

        if (!empty($independent_activity->thumb_url) && filter_var($independent_activity->thumb_url, FILTER_VALIDATE_URL) == false) {
            $activity_thumbanil =  storage_path("app/public/" . (str_replace('/storage/', '', $independent_activity->thumb_url)));
            $ext = pathinfo(basename($activity_thumbanil), PATHINFO_EXTENSION);
            if(!is_dir($activity_thumbanil) && file_exists($activity_thumbanil)) {
                $activity_thumbanil_file = '/exports/' . $activity_dir_name . '/' . basename($activity_thumbanil);
                Storage::disk('public')->put($activity_thumbanil_file, file_get_contents($activity_thumbanil));
            }
        }
        $exported_content_dir_path = 'app/public/exports/' . $activity_dir_name . '/' . $independent_activity->h5p_content_id;
        $exported_content_dir = storage_path($exported_content_dir_path);
        \File::copyDirectory( storage_path('app/public/h5p/content/'.$independent_activity->h5p_content_id), $exported_content_dir );
    
        // Get real path for our folder
        $rootPath = storage_path('app/public/exports/'.$activity_dir_name);

        // Initialize archive object
        $zip = new ZipArchive();
        $fileName = $activity_dir_name.'.zip';
        $zip->open(storage_path('app/public/exports/'.$fileName), ZipArchive::CREATE | ZipArchive::OVERWRITE);

        // Create recursive directory iterator
        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file)
        {
            // Skip directories (they would be added automatically)
            if (!$file->isDir())
            {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);

                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        // Remove independent activity folder after creation of zip
        $this->rrmdir(storage_path('app/public/exports/'.$activity_dir_name));

        // Remove independent activity folder after creation of zip
        $this->rrmdir(storage_path('app/public/exports/'.$activity_dir_name));

        return storage_path('app/public/exports/' . $fileName);
    }

    /**
     * To Deleted the directory recurcively
     *
     * @param $dir
     */
    private function rrmdir($dir) {
        if (is_dir($dir)) {
          $objects = scandir($dir);
          foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
              if (filetype($dir . "/" . $object) == "dir") $this->rrmdir($dir . "/" . $object); else unlink($dir . "/" . $object);
            }
          }
          reset($objects);
          rmdir($dir);
        }
     }

     /**
     * To import project and associated playlists
     *
     * @param $authUser
     * @param $path
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function importIndependentActivity($authUser, $path, $suborganization_id, $method_source="API")
    {
        try {

            $zip = new ZipArchive;
            $source_file = storage_path("app/public/" . (str_replace('/storage/', '', $path)));

            if ($method_source === "command") {
                $source_file = $path;
            }

            if ($zip->open($source_file) === TRUE) {
                $extracted_folder = "app/public/imports/independent-activity-".uniqid();
                $zip->extractTo(storage_path($extracted_folder.'/'));
                $zip->close();
            }else {
                $return_res = [
                    "success"=> false,
                    "message" => "Unable to import Activity."
                ];
                return json_encode($return_res);
            }
            return DB::transaction(function () use ($extracted_folder, $suborganization_id, $authUser, $source_file, $method_source) {
                if (file_exists(storage_path($extracted_folder.'/activity.json'))) {
                    
                    $activity_json = file_get_contents(storage_path($extracted_folder . '/activity.json'));
                    $activity = json_decode($activity_json,true);

                    $old_content_id = $activity['h5p_content_id'];

                    unset($activity["id"], $activity["playlist_id"], $activity["created_at"], $activity["updated_at"], $activity["h5p_content_id"]);

                    $content_json = file_get_contents(
                                            storage_path($extracted_folder . '/' . $old_content_id . '.json'));
                    $h5p_content = json_decode($content_json,true);
                    $h5p_content['library_id'] = DB::table('h5p_libraries')
                                                        ->where('name', $h5p_content['library_title'])
                                                        ->where('major_version',$h5p_content['library_major_version'])
                                                        ->where('minor_version',$h5p_content['library_minor_version'])
                                                        ->value('id');

                    unset($h5p_content["id"], $h5p_content["user_id"], $h5p_content["created_at"], $h5p_content["updated_at"],
                                                $h5p_content['library_title'], $h5p_content['library_major_version'], $h5p_content['library_minor_version']);

                    $h5p_content['user_id'] = $authUser->id;

                    $new_content = DB::table('h5p_contents')->insert($h5p_content);
                    $new_content_id = DB::getPdo()->lastInsertId();

                    \File::copyDirectory(
                                storage_path($extracted_folder . '/' . $old_content_id),
                                storage_path('app/public/h5p/content/'.$new_content_id)
                            );
                    
                    // Move Content to editor Folder
                    $destinationEditorFolderPath = $extracted_folder . $old_content_id;

                    // Move editor images
                    \File::copyDirectory(
                        storage_path($destinationEditorFolderPath . '/images/'),
                        storage_path('app/public/h5p/editor/images/')
                    );

                    // Move editor audios
                    \File::copyDirectory(
                        storage_path($destinationEditorFolderPath . '/audios/'),
                        storage_path('app/public/h5p/editor/audios/')
                    );

                    // Move editor videos
                    \File::copyDirectory(
                        storage_path($destinationEditorFolderPath . '/videos/'),
                        storage_path('app/public/h5p/editor/videos/')
                    );

                    // Move editor files
                    \File::copyDirectory(
                        storage_path($destinationEditorFolderPath . '/files/'),
                        storage_path('app/public/h5p/editor/files/')
                    );

                    $activity['h5p_content_id'] = $new_content_id;

                    if (!empty($activity['thumb_url']) && filter_var($activity['thumb_url'], FILTER_VALIDATE_URL) === false) {
                        $activitiy_thumbnail_path = storage_path(
                                                        $extracted_folder . '/' . basename($activity['thumb_url'])
                                                    );
                        if(file_exists($activitiy_thumbnail_path)) {
                            $ext = pathinfo(basename($activity['thumb_url']), PATHINFO_EXTENSION);
                            $new_image_name = uniqid() . '.' . $ext;
                            $destination_file = storage_path('app/public/independent-activities/'.$new_image_name);
                            $source_file = $extracted_folder . '/' . basename($activity['thumb_url']);
                            \File::copy(
                                storage_path($source_file), $destination_file
                                );
                            $activity['thumb_url'] = "/storage/independent-activities/" . $new_image_name;
                        }
                    }

                    $cloned_activity = $this->create($activity);

                    // Import Activity Subjects
                    $projectOrganizationId = $activity['organization_id'];

                    $activitySubjectPath = storage_path($extracted_folder . '/activity_subject.json');
                    if (file_exists($activitySubjectPath)) {
                        $subjectContent = file_get_contents($activitySubjectPath);
                        $subjects = json_decode($subjectContent,true);
                        \Log::info($subjects);
                        foreach ($subjects as $subject) {

                            $recSubject = Subject::firstOrCreate(['name' => $subject['name'], 'organization_id'=>$projectOrganizationId]);

                            $newSubject['independent_activity_id'] = $cloned_activity->id;
                            $newSubject['subject_id'] = $recSubject->id;
                            $newSubject['created_at'] = date('Y-m-d H:i:s');
                            $newSubject['updated_at'] = date('Y-m-d H:i:s');
                            
                            DB::table('independent_activity_subject')->insert($newSubject);
                        }
                    }

                    // Import Activity Education-Level

                    $activtyEducationPath = storage_path($extracted_folder . '/activity_education_level.json');
                    if (file_exists($activtyEducationPath)) {
                        $educationLevelContent = file_get_contents($activtyEducationPath);
                        $educationLevels = json_decode($educationLevelContent,true);
                        \Log::info($educationLevels);
                        foreach ($educationLevels as $educationLevel) {

                            $recEducationLevel = EducationLevel::firstOrCreate(['name' => $educationLevel['name'], 'organization_id'=>$projectOrganizationId]);

                            $newEducationLevel['independent_activity_id'] = $cloned_activity->id;
                            $newEducationLevel['education_level_id'] = $recEducationLevel->id;
                            $newEducationLevel['created_at'] = date('Y-m-d H:i:s');
                            $newEducationLevel['updated_at'] = date('Y-m-d H:i:s');
                            
                            DB::table('independent_activity_education_level')->insert($newEducationLevel);
                        }
                    }

                    // Import Activity Author-Tag

                    $authorTagPath = storage_path($extracted_folder . '/activity_author_tag.json');
                    if (file_exists($authorTagPath)) {
                        $authorTagContent = file_get_contents($authorTagPath);
                        $authorTags = json_decode($authorTagContent,true);
                        \Log::info($authorTags);
                        foreach ($authorTags as $authorTag) {
                            $recAuthorTag = AuthorTag::firstOrCreate(['name' => $authorTag['name'], 'organization_id'=>$projectOrganizationId]);
                            $newauthorTag['independent_activity_id'] = $cloned_activity->id;
                            $newauthorTag['author_tag_id'] = $recAuthorTag->id;
                            $newauthorTag['created_at'] = date('Y-m-d H:i:s');
                            $newauthorTag['updated_at'] = date('Y-m-d H:i:s');
                            
                            DB::table('independent_activity_author_tag')->insert($newauthorTag);
                        }
                    }
                    $this->rrmdir(storage_path($extracted_folder)); // Deleted the storage extracted directory

                    if ($method_source !== "command") {
                        unlink($source_file); // Deleted the storage zip file
                    } else {

                        $return_res = [
                            "success"=> true,
                            "message" => "Independent Activity has been imported successfully",
                            "project_id" => $cloned_activity->id
                        ];
                        return json_encode($return_res);
                    }

                    return $activity['title'];
                }
            });


        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            if ($method_source === "command") {
                $return_res = [
                    "success"=> false,
                    "message" => "Unable to import the activity, please try again later!"
                ];
                return(json_encode($return_res));
            }

            throw new GeneralException('Unable to import the activity, please try again later!');
        }
    }

    /**
     * Update Indexes for independent activities and related models
     * @param $independentActivity
     * @param $index
     * @return string
     * @throws GeneralException
     */
    public function updateIndex($independentActivity, $index): string
    {
        if (! isset($this->model::$indexing[$index])){
            throw new GeneralException('Invalid Library value provided.');
        }
        $independentActivity->update(['indexing' => $index]);
        return 'Library status changed successfully!';
    }

    /**
     * Copy Exisiting independentent activity into a playlist
     * @param $independentActivity
     * @param $playlist
     * @param $token
     * @return string
     * 
     */
    public function copyToPlaylist($independentActivity, $playlist, $token)
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

        $new_thumb_url = clone_thumbnail($independentActivity->thumb_url, "independent-activities");
        $activity_data = [
            'title' => $independentActivity->title,
            'type' => $independentActivity->type,
            'content' => $independentActivity->content,
            'playlist_id' => $playlist->id,
            'order' => $this->getOrder($playlist->id) + 1,
            'h5p_content_id' => $newH5pContent, // set if new h5pContent created
            'thumb_url' => $new_thumb_url,
            'shared' => $playlist->project->shared,
        ];
        
        $cloned_activity = Activity::create($activity_data);

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
     * Get indep-activities of a user who is launching the deeplink from another LMS
     * @param $data
     * @param $user
     * @return mixed
     */
    public function independentActivities($data, $user)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;
        $q = $data['query'] ?? null;

        // if simple request for getting independent activity listing with search
        if ($q) {
            $query = $query->where('title', 'iLIKE', '%' . $q . '%');
        }

        return $query->where('user_id', $user)->orderBy('order', 'ASC')->paginate($perPage)->withQueryString();
    }

     /**
     * Copy Exisiting independentent activity into a playlist
     * @param $independentActivity
     * @param $playlist
     * @param $token
     * @return string
     * 
     */
    public function moveToPlaylist($independentActivity, $playlist, $token)
    {
        $newThumbUrl = cloneIndependentActivityThumbnail($independentActivity->thumb_url, "independent-activities", "activities");
        
        $activity_data = [
            'title' => $independentActivity->title,
            'type' => $independentActivity->type,
            'content' => $independentActivity->content,
            'playlist_id' => $playlist->id,
            'order' => $this->getOrder($playlist->id) + 1,
            'h5p_content_id' => $independentActivity->h5p_content_id, // Move the content 
            'thumb_url' => $newThumbUrl,
            'shared' => $playlist->project->shared,
        ];
        
        $cloned_activity = Activity::create($activity_data);
        
        if ($cloned_activity && count($independentActivity->subjects) > 0) {
            $cloned_activity->subjects()->attach($independentActivity->subjects);
        }
        if ($cloned_activity && count($independentActivity->educationLevels) > 0) {
            $cloned_activity->educationLevels()->attach($independentActivity->educationLevels);
        }
        if ($cloned_activity && count($independentActivity->authorTags) > 0) {
            $cloned_activity->authorTags()->attach($independentActivity->authorTags);
        }
        $this->delete($independentActivity->id); // Remove independent activity
        return $cloned_activity['id'];
    }

    /**
     * Copy Exisiting activity into an independentent Activity
     * @param $organization
     * @param $activity
     * @param $token
     * @return int
     * 
     */
    public function convertIntoIndependentActivity($organization, $activity, $token)
    {
        $h5p_content = $activity->h5p_content;
        if ($h5p_content) {
            $h5p_content = $h5p_content->replicate(); // replicate the all data of original activity h5pContent relation
            $h5p_content->user_id = get_user_id_by_token($token); // just update the user id which is performing the cloning
            $h5p_content->save(); // this will return true, then we can get id of h5pContent
        }
        $newH5pContent = $h5p_content->id ?? null;

        // copy the content data if exist
        $this->copy_content_data($activity->h5p_content_id, $newH5pContent);

        $newThumbUrl = cloneIndependentActivityThumbnail($activity->thumb_url, "activities", "independent-activities");
        
        $independentActivityData = [
            'title' => $activity->title,
            'type' => $activity->type,
            'content' => $activity->content,
            'h5p_content_id' => $newH5pContent, // set if new h5pContent created
            'thumb_url' => $newThumbUrl,
            'user_id' => get_user_id_by_token($token),
            'shared' => 0,
            'order' => $this->getOrder($organization->id) + 1,
            'organization_id' => $organization->id,
            'organization_visibility_type_id' => config('constants.private-organization-visibility-type-id'),
        ];
        
        $clonedActivity = $this->create($independentActivityData);

        if ($clonedActivity && count($activity->subjects) > 0) {
            $clonedActivity->subjects()->attach($activity->subjects);
        }
        if ($clonedActivity && count($activity->educationLevels) > 0) {
            $clonedActivity->educationLevels()->attach($activity->educationLevels);
        }
        if ($clonedActivity && count($activity->authorTags) > 0) {
            $clonedActivity->authorTags()->attach($activity->authorTags);
        }

        return $clonedActivity['id'];
        
    }
    
}
