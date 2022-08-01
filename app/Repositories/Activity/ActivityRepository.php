<?php

namespace App\Repositories\Activity;

use App\User;
use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Models\Organization;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Repositories\Subject\SubjectRepositoryInterface;
use App\Repositories\EducationLevel\EducationLevelRepositoryInterface;
use App\Http\Resources\V1\SearchResource;
use App\Http\Resources\V1\SearchPostgreSqlResource;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\EducationLevel;
use App\Models\AuthorTag;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;
    private $subjectRepository;
    private $educationLevelRepository;
    private $client;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     * @param H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository
     * @param SubjectRepositoryInterface $subjectRepository
     * @param EducationLevelRepositoryInterface $educationLevelRepository
     */
    public function __construct(
        Activity $model,
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
     * @param $organization_id
     * @param $data
     * @return mixed
     */
    public function getStandAloneActivities($organization_id, $data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $auth_user = auth()->user();

        $query = $this->model;
        $q = $data['query'] ?? null;

        if ($q) {
            $query = $query->where('title', 'iLIKE', '%' .$q. '%');
        }

        return $query->where('organization_id', $organization_id)
                     ->where('user_id', $auth_user->id)
                     ->paginate($perPage)->withQueryString();
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
        $is_updated = $this->model->where('id', $id)->update($attributes);

        if ($is_updated) {
            $this->model->where('id', $id)->searchable();
        }

        return $is_updated;
    }

    /**
     * Get the search request
     *
     * @param array $data
     * @return array
     */
    public function searchForm($data)
    {
        $projects = [];

        $searchedModels = $this->model->searchForm()
            ->query(Arr::get($data, 'query', 0))
            ->join(Project::class, Playlist::class)
            ->indexing([config('constants.indexing-approved')])
            ->organizationIds(Arr::get($data, 'organizationIds', []))
            ->organizationVisibilityTypeIds([config('constants.public-organization-visibility-type-id')])
            ->sort(Arr::get($data, 'sort', '_id'), Arr::get($data, 'order', 'desc'))
            ->from(Arr::get($data, 'from', 0))
            ->size(Arr::get($data, 'size', 10))
            ->execute()
            ->models();

        foreach ($searchedModels as $modelId => $searchedModel) {
            $modelName = class_basename($searchedModel);

            if ($modelName === 'Project' && !isset($projects[$searchedModel->id])) {
                $projects[$searchedModel->id] = SearchResource::make($searchedModel)->resolve();
                $projects[$searchedModel->id]['playlists'] = [];
            } elseif ($modelName === 'Playlist' && !isset($projects[$searchedModel->project_id]['playlists'][$searchedModel->id])) {
                $playlistProjectId = $searchedModel->project_id;

                if (!isset($projects[$playlistProjectId])) {
                    $projects[$playlistProjectId] = SearchResource::make($searchedModel->project)->resolve();
                    $projects[$playlistProjectId]['playlists'] = [];
                }

                $projects[$playlistProjectId]['playlists'][$searchedModel->id] = SearchResource::make($searchedModel)->resolve();
                $projects[$playlistProjectId]['playlists'][$searchedModel->id]['activities'] = [];
            } elseif ($modelName === 'Activity') {
                $activityId = $searchedModel->id;
                $activityPlaylist = $searchedModel->playlist;
                $activityPlaylistId = $activityPlaylist->id;
                $activityPlaylistProjectId = $activityPlaylist->project_id;

                if (!isset($projects[$activityPlaylistProjectId]['playlists'][$activityPlaylistId]['activities'][$activityId])) {
                    if (!isset($projects[$activityPlaylistProjectId])) {
                        $projects[$activityPlaylistProjectId] = SearchResource::make($activityPlaylist->project)->resolve();
                        $projects[$activityPlaylistProjectId]['playlists'] = [];
                    }

                    if (!isset($projects[$activityPlaylistProjectId]['playlists'][$activityPlaylistId])) {
                        $projects[$activityPlaylistProjectId]['playlists'][$activityPlaylistId] = SearchResource::make($activityPlaylist)->resolve();
                        $projects[$activityPlaylistProjectId]['playlists'][$activityPlaylistId]['activities'] = [];
                    }

                    $projects[$activityPlaylistProjectId]['playlists'][$activityPlaylistId]['activities'][$activityId] = SearchResource::make($searchedModel)->resolve();
                }
            }
        }

        return $projects;
    }

    /**
     * Get the advance elasticsearch request
     *
     * @param array $data
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function advanceElasticsearchForm($data)
    {

        $counts = [];
        $projectIds = [];
        $organizationParentChildrenIds = [];

        if (isset($data['searchType']) && $data['searchType'] === 'showcase_projects') {
            $organization = $data['orgObj'];
            $organizationParentChildrenIds = resolve(OrganizationRepositoryInterface::class)->getParentChildrenOrganizationIds($organization);
        }

        if (isset($data['userIds']) && !empty($data['userIds'])) {
            $userIds = $data['userIds'];

            $projectIds = Project::whereHas('users', function (Builder $query) use ($userIds) {
                $query->whereIn('id', $userIds);
            })->pluck('id')->toArray();
        }

        if (isset($data['author']) && !empty($data['author'])) {
            $author = $data['author'];

            $authorProjectIds = Project::whereHas('users', function (Builder $query) use ($author) {
                $query->where('first_name', 'like', '%' . $author . '%')
                        ->orWhere('last_name', 'like', '%' . $author . '%')
                        ->orWhere('email', 'like', '%' . $author . '%');
            })->pluck('id')->toArray();

            if (empty($authorProjectIds)) {
                if (empty($projectIds)) {
                    $projectIds = [0];
                }
            } else {
                $projectIds = array_merge($projectIds, $authorProjectIds);
            }
        }

        if (isset($data['userIds']) && !empty($data['userIds']) && empty($projectIds)) {
            $projectIds = [0];
        }

        $searchResultQuery = $this->model->searchForm()
            ->searchType(Arr::get($data, 'searchType', 0))
            ->organizationParentChildrenIds($organizationParentChildrenIds)
            ->query(Arr::get($data, 'query', 0))
            ->join(Project::class, Playlist::class)
            ->aggregate('count_by_index', [
                'terms' => [
                    'field' => '_index',
                ]
            ])
            ->organizationIds(Arr::get($data, 'organizationIds', []))
            ->organizationVisibilityTypeIds(Arr::get($data, 'organizationVisibilityTypeIds', []))
            ->type(Arr::get($data, 'type', 0))
            ->startDate(Arr::get($data, 'startDate', 0))
            ->endDate(Arr::get($data, 'endDate', 0))
            ->indexing(Arr::get($data, 'indexing', []))
            ->subjectIds(Arr::get($data, 'subjectIds', []))
            ->educationLevelIds(Arr::get($data, 'educationLevelIds', []))
            ->projectIds($projectIds)
            ->h5pLibraries(Arr::get($data, 'h5pLibraries', []))
            ->negativeQuery(Arr::get($data, 'negativeQuery', 0))
            ->sort('created_at', "desc")
            ->from(Arr::get($data, 'from', 0))
            ->size(Arr::get($data, 'size', 10));

        if (isset($data['model']) && !empty($data['model'])) {
            $searchResultQuery = $searchResultQuery->postFilter('term', ['_index' => $data['model']]);
        }

        $searchResult = $searchResultQuery->execute();

        $aggregations = $searchResult->aggregations();
        $countByIndex = $aggregations->get('count_by_index');

        if (isset($countByIndex['buckets'])) {
            foreach ($countByIndex['buckets'] as $indexData) {
                $counts[$indexData['key']] = $indexData['doc_count'];
            }
        }

        $counts['total'] = array_sum($counts);

        return (SearchResource::collection($searchResult->models()))->additional(['meta' => $counts]);
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
        $queryFrom = 0;
        $querySize = 10;

        if (isset($data['searchType']) && $data['searchType'] === 'showcase_projects') {
            $organization = $data['orgObj'];
            $organizationParentChildrenIds = resolve(OrganizationRepositoryInterface::class)->getParentChildrenOrganizationIds($organization);
        }

        if ($authUser) {
            $query = 'SELECT * FROM advSearch(:user_id, :query_text, :query_subject, :query_education, :query_tags)';

            $queryParams['user_id'] = $authUser;
        } else {
            $query = 'SELECT * FROM advSearch(:query_text, :query_subject, :query_education, :query_tags)';
        }

        $countsQuery = 'SELECT entity, count(1) FROM (' . $query . ')sq GROUP BY entity';
        $queryWhere[] = "deleted_at IS NULL";
        $queryWhere[] = "(standalone_activity_user_id IS NULL OR standalone_activity_user_id = 0)";
        $modelMapping = ['projects' => 'Project', 'playlists' => 'Playlist', 'activities' => 'Activity'];

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
            $data['h5pLibraries'] = array_map(
                                        function($n) {
                                            return "h5plib LIKE '" . explode(" ",$n)[0] . "%'";
                                        },
                                        $data['h5pLibraries']
                                    );
            $queryWhereH5pLibraries = implode(' OR ', $data['h5pLibraries']);
            $queryWhere[] = "(" . $queryWhereH5pLibraries . ")";
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
            $queryWhere[] = "(name NOT LIKE '%" . $data['negativeQuery'] . "%' OR description NOT LIKE '%" . $data['negativeQuery'] . "%')";
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
     * Get the H5P Elasticsearch Field Values.
     *
     * @param Object $h5pContent
     * @return array
     */
    public function getH5pElasticsearchFields($h5pContent)
    {
        $h5pElasticsearchFieldsArray = [];

        if (isset($h5pContent) && $h5pContent['parameters']) {
            $parameters = json_decode($h5pContent['parameters'], true);

            $h5pElasticsearchFields = $this->h5pElasticsearchFieldRepository->model->where([
                ['library_id', '=', $h5pContent['library_id']],
                ['indexed', '=', true],
            ])->get();

            foreach ($h5pElasticsearchFields as $h5pElasticsearchField) {
                $h5pElasticsearchFieldArray = explode('.group.nested.array.', $h5pElasticsearchField->path);

                if (count($h5pElasticsearchFieldArray) > 1) {
                    $h5pElasticsearchFieldValueArrays = Arr::get($parameters, $h5pElasticsearchFieldArray[0]);

                    if (is_array($h5pElasticsearchFieldValueArrays)) {
                        foreach ($h5pElasticsearchFieldValueArrays as $h5pElasticsearchFieldValueArray) {
                            if (isset($h5pElasticsearchFieldValueArray[$h5pElasticsearchFieldArray[1]])) {
                                $h5pElasticsearchFieldValue = $h5pElasticsearchFieldValueArray[$h5pElasticsearchFieldArray[1]];
                                $h5pElasticsearchFieldsArray[$h5pElasticsearchField->path][] = $h5pElasticsearchFieldValue;
                            }
                        }
                    }
                } else {
                    $h5pElasticsearchFieldValue = Arr::get($parameters, $h5pElasticsearchField->path, null);

                    if ($h5pElasticsearchFieldValue !== null) {
                        $h5pElasticsearchFieldsArray[$h5pElasticsearchField->path] = $h5pElasticsearchFieldValue;
                    }
                }
            }
        }

        return $h5pElasticsearchFieldsArray;
    }

    /**
     * To clone Activity
     * @param Playlist $playlist
     * @param Activity $activity
     * @param string $token
     * @return int
     */
    public function clone(Playlist $playlist, Activity $activity, $token)
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

        // detect is it duplicate request or clone
        $isDuplicate = $activity->playlist_id === $playlist->id;

        if ($isDuplicate) {
            $this->model->where('playlist_id', $activity->playlist_id)->where('order', '>', $activity->order)->increment('order', 1);
        }

        $new_thumb_url = clone_thumbnail($activity->thumb_url, "activities");
        $activity_data = [
            'title' => ($isDuplicate) ? $activity->title . "-COPY" : $activity->title,
            'type' => $activity->type,
            'content' => $activity->content,
            'playlist_id' => $playlist->id,
            'order' => ($isDuplicate) ? $activity->order + 1 : $this->getOrder($playlist->id) + 1,
            'h5p_content_id' => $newH5pContent, // set if new h5pContent created
            'thumb_url' => $new_thumb_url,
            'subject_id' => $activity->subject_id,
            'education_level_id' => $activity->education_level_id,
            'shared' => $activity->shared,
        ];
        $cloned_activity = $this->create($activity_data);

        if ($cloned_activity && count($activity->subjects) > 0) {
            $cloned_activity->subjects()->attach($activity->subjects);
        }
        if ($cloned_activity && count($activity->educationLevels) > 0) {
            $cloned_activity->educationLevels()->attach($activity->educationLevels);
        }
        if ($cloned_activity && count($activity->authorTags) > 0) {
            $cloned_activity->authorTags()->attach($activity->authorTags);
        }

        return $cloned_activity['id'];
    }

    /**
     * To clone Stand Alone Activity
     * @param Activity $activity
     * @param string $token
     * @return int
     */
    public function cloneStandAloneActivity(Activity $activity, $token)
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

        $new_thumb_url = clone_thumbnail($activity->thumb_url, "activities");
        $activity_data = [
            'title' => $activity->title . "-COPY",
            'type' => $activity->type,
            'content' => $activity->content,
            'description' => $activity->description,
            'order' => $activity->order + 1,
            'h5p_content_id' => $newH5pContent, // set if new h5pContent created
            'thumb_url' => $new_thumb_url,
            'subject_id' => $activity->subject_id,
            'education_level_id' => $activity->education_level_id,
            'shared' => $activity->shared,
            'user_id' => $activity->user_id,
            'organization_id' => $activity->organization_id,
        ];
        $cloned_activity = $this->create($activity_data);

        if ($cloned_activity && count($activity->subjects) > 0) {
            $cloned_activity->subjects()->attach($activity->subjects);
        }
        if ($cloned_activity && count($activity->educationLevels) > 0) {
            $cloned_activity->educationLevels()->attach($activity->educationLevels);
        }
        if ($cloned_activity && count($activity->authorTags) > 0) {
            $cloned_activity->authorTags()->attach($activity->authorTags);
        }

        return $cloned_activity['id'];
    }

    /**
     * To export and import an H5p File
     *
     * @param $token
     * @param int $h5p_content_id
     */
    public function download_and_upload_h5p($token, $h5p_content_id)
    {
        $new_h5p_file = uniqid() . '.h5p';

        $response = null;
        try {
            $response = $this->client->request('GET', config('app.url') . '/api/v1/h5p/export/' . $h5p_content_id, ['sink' => storage_path('app/public/uploads/' . $new_h5p_file), 'http_errors' => false]);
        } catch (Exception $ex) {

        }

        if (!is_null($response) && $response->getStatusCode() == 200) {
            $response_h5p = null;
            try {
                $response_h5p = $this->client->request('POST', config('app.url') . '/api/v1/h5p', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                    ],
                    'multipart' => [
                        [
                            'name' => 'action',
                            'contents' => 'upload'
                        ],
                        [
                            'name' => 'h5p_file',
                            'contents' => fopen(storage_path('app/public/uploads/' . $new_h5p_file), 'r')
                        ]
                    ]
                ]);
            } catch (Excecption $ex) {

            }

            if (!is_null($response_h5p) && $response_h5p->getStatusCode() == 201) {
                unlink(storage_path('app/public/uploads/' . $new_h5p_file));
                return json_decode($response_h5p->getBody()->getContents());
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Check is playlist public
     * @param $playlistId
     * @return false
     */
    public function getPlaylistIsPublicValue($playlistId)
    {
        $playlist = Playlist::where('id', $playlistId)->with('project')->first();
        return ($playlist->project->indexing === (int)config('constants.indexing-approved')) ? $playlist : false;
    }

    /**
     * Get latest order of activity for Playlist
     * @param $playlist_id
     * @return int
     */
    public function getOrder($playlist_id)
    {
        return $this->model->where('playlist_id', $playlist_id)
            ->orderBy('order', 'desc')
            ->value('order') ?? 0;
    }

    /**
     * To Populate missing order number, One time script
     */
    public function populateOrderNumber()
    {
        $playLists = Playlist::all();
        foreach($playLists as $playlist) {
            $activites = $playlist->activities()->whereNull('order')->orderBy('created_at')->get();
            if(!empty($activites)) {
                $order = 1;
                foreach($activites as $activity) {
                    $activity->order = $order;
                    $activity->save();
                    $order++;
                }
            }
        }
    }

    /**
     * Gets activities for LTI external tool search from CurrikiGo
     * @param array $data
     * @return array
     */
    public function ltiSearchForm($request)
    {
        // Fetch Elastic Search results
        $data = [
            'query' => $request->input('query', ''),
            'from' => $request->input('from', 0),
            'size' => 11,
            'model' => 'projects',
            'indexing' => intval($request->input('private', 0)) === 1 ? [] : [3],
            'searchType' => 'org_projects_admin'
        ];
        if ($request->input('private') === "Select all") {
            unset($data['indexing']);
        }

        $user = User::where('email', $request->input('userEmail'))->first();

        // Check LMS settings for authorization when searching private projects
        if (empty($data['indexing'])) {
            // There can be many LmsSettings for different users sharing the same
            // lti_client_id. Need to find the user first

            $lmsSetting = LmsSetting::where('lti_client_id', $request->input('ltiClientId'))
            ->where('user_id', $user->id)
                ->first();

            if (empty($user) || empty($lmsSetting)) {
                return [];
            } else {
                $data['userIds'] = [$lmsSetting->user_id];
            }
        }

        // If a an author is provided, limit to projects from that user only
        if ($request->has('author') && $request->input('private', 0) !== '1') {
            $authors = User::where('email', 'like', '%' . $request->input('author') . '%')
                ->orWhere('name', 'like', '%' . $request->input('author') . '%')
                ->orWhere('first_name', 'like', '%' . $request->input('author') . '%')
                ->orWhere('last_name', 'like', '%' . $request->input('author') . '%')
                ->pluck('id');

            if ($authors->isEmpty()) {
                return [];
            }
            $data['userIds'] = $authors->toArray();
        }

        $data['organizationIds'] = $request->has('org') ? [intval($request->input('org'))] : [];
        $data['subjectIds'] = $request->has('subjectIds') || $request->has('subject') ? $request->input('subjectIds') : [];
        $data['educationLevelIds'] = $request->has('educationLevelIds') || $request->has('level') ? $request->input('educationLevelIds') : [];

        if ($request->has('start')) {
            $data['startDate'] = $request->input('start', '');
        }

        if ($request->has('end')) {
            $data['endDate'] = $request->input('end', '');
        }

        return $this->advanceSearchForm($data);
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
     * To clone Activity
     * @param Playlist $playlist
     * @param Activity $authUser
     * @param string $playlist_dir
     * @param string $activity_dir
     * @param string $extracted_folder
     *
     */
    public function importActivity(Playlist $playlist, $authUser, $playlist_dir, $activity_dir, $extracted_folder)
    {
        $activity_json = file_get_contents(
                                storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/' .
                                                                    $activity_dir . '/' . $activity_dir . '.json'));
        $activity = json_decode($activity_json,true);

        $old_content_id = $activity['h5p_content_id'];

        unset($activity["id"], $activity["playlist_id"], $activity["created_at"], $activity["updated_at"], $activity["h5p_content_id"]);

        $activity['playlist_id'] = $playlist->id; //assign playlist to activities

        $content_json = file_get_contents(
                                storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/' .
                                                                    $activity_dir . '/' . $old_content_id . '.json'));
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
                    storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/' . $activity_dir . '/' . $old_content_id),
                    storage_path('app/public/h5p/content/'.$new_content_id)
                );

        
        // Move Content to editor Folder

        $destinationEditorFolderPath = $extracted_folder . '/playlists/' . $playlist_dir . '/activities/' . $activity_dir . '/' . $old_content_id;
        
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
                                            $extracted_folder . '/playlists/'.$playlist_dir . '/activities/' .
                                                        $activity_dir . '/' . basename($activity['thumb_url'])
                                        );
            if(file_exists($activitiy_thumbnail_path)) {
                $ext = pathinfo(basename($activity['thumb_url']), PATHINFO_EXTENSION);
                $new_image_name = uniqid() . '.' . $ext;
                $destination_file = storage_path('app/public/activities/'.$new_image_name);
                $source_file = $extracted_folder . '/playlists/' . $playlist_dir . '/activities/' .
                                                $activity_dir . '/' . basename($activity['thumb_url']);
                \File::copy(
                    storage_path($source_file), $destination_file
                    );
                $activity['thumb_url'] = "/storage/activities/" . $new_image_name;
            }
        }

        $cloned_activity = $this->create($activity);

        // Import Activity Subjects
        $projectOrganizationId = Project::where('id',$playlist->project_id)->value('organization_id');
        
        $activitySubjectPath = storage_path($extracted_folder . '/playlists/' . $playlist_dir . 
                                                                '/activities/' . $activity_dir . '/activity_subject.json');
        if (file_exists($activitySubjectPath)) {
            $subjectContent = file_get_contents($activitySubjectPath);
            $subjects = json_decode($subjectContent,true);
            \Log::info($subjects);
            foreach ($subjects as $subject) {
    
                $recSubject = Subject::firstOrCreate(['name' => $subject['name'], 'organization_id'=>$projectOrganizationId]);
    
                $newSubject['activity_id'] = $cloned_activity->id;
                $newSubject['subject_id'] = $recSubject->id;
                $newSubject['created_at'] = date('Y-m-d H:i:s');
                $newSubject['updated_at'] = date('Y-m-d H:i:s');
                
                DB::table('activity_subject')->insert($newSubject);
            }
        }
        
        // Import Activity Education-Level

        $activtyEducationPath = storage_path($extracted_folder . '/playlists/' . $playlist_dir . '/activities/' .
                                                                    $activity_dir . '/activity_education_level.json');
        if (file_exists($activtyEducationPath)) {
            $educationLevelContent = file_get_contents($activtyEducationPath);
            $educationLevels = json_decode($educationLevelContent,true);
            \Log::info($educationLevels);
            foreach ($educationLevels as $educationLevel) {
    
                $recEducationLevel = EducationLevel::firstOrCreate(['name' => $educationLevel['name'], 'organization_id'=>$projectOrganizationId]);
    
                $newEducationLevel['activity_id'] = $cloned_activity->id;
                $newEducationLevel['education_level_id'] = $recEducationLevel->id;
                $newEducationLevel['created_at'] = date('Y-m-d H:i:s');
                $newEducationLevel['updated_at'] = date('Y-m-d H:i:s');
                
                DB::table('activity_education_level')->insert($newEducationLevel);
            }
        }

        // Import Activity Author-Tag

        $authorTagPath = storage_path($extracted_folder . '/playlists/' . $playlist_dir . 
                                                            '/activities/' . $activity_dir . '/activity_author_tag.json');
        if (file_exists($authorTagPath)) {
            $authorTagContent = file_get_contents($authorTagPath);
            $authorTags = json_decode($authorTagContent,true);
            \Log::info($authorTags);
            foreach ($authorTags as $authorTag) {
                $recAuthorTag = AuthorTag::firstOrCreate(['name' => $authorTag['name'], 'organization_id'=>$projectOrganizationId]);
                $newauthorTag['activity_id'] = $cloned_activity->id;
                $newauthorTag['author_tag_id'] = $recAuthorTag->id;
                $newauthorTag['created_at'] = date('Y-m-d H:i:s');
                $newauthorTag['updated_at'] = date('Y-m-d H:i:s');
                
                DB::table('activity_author_tag')->insert($newauthorTag);
            }
        }
    }

}
