<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Models\Playlist;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Project;
use Illuminate\Support\Arr;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Http\Resources\V1\SearchResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;
    private $client;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     * @param H5pElasticsearchFieldRepositoryInterface $activityRepository
     */
    public function __construct(Activity $model, H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository)
    {
        parent::__construct($model);
        $this->client = new \GuzzleHttp\Client();
        $this->h5pElasticsearchFieldRepository = $h5pElasticsearchFieldRepository;
    }

    /**
     * Get the search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function searchForm($data)
    {
        $projects = [];

        $searchedModels = $this->model->searchForm()
                            ->query(Arr::get($data, 'query', 0))
                            ->join(Project::class, Playlist::class)
                            ->isPublic(true)
                            ->elasticsearch(true)
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
                $playlistProjectid = $searchedModel->project_id;

                if (!isset($projects[$playlistProjectid])) {
                    $projects[$playlistProjectid] = SearchResource::make($searchedModel->project)->resolve();
                    $projects[$playlistProjectid]['playlists'] = [];
                }

                $projects[$playlistProjectid]['playlists'][$searchedModel->id] = SearchResource::make($searchedModel)->resolve();
                $projects[$playlistProjectid]['playlists'][$searchedModel->id]['activities'] = [];
            } elseif ($modelName === 'Activity') {
                $activityId = $searchedModel->id;
                $activityPlaylist = $searchedModel->playlist;
                $activityPlaylistId = $activityPlaylist->id;
                $activityPlaylistProjectid = $activityPlaylist->project_id;

                if (!isset($projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'][$activityId])) {
                    if (!isset($projects[$activityPlaylistProjectid])) {
                        $projects[$activityPlaylistProjectid] = SearchResource::make($activityPlaylist->project)->resolve();
                        $projects[$activityPlaylistProjectid]['playlists'] = [];
                    }

                    if (!isset($projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId])) {
                        $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId] = SearchResource::make($activityPlaylist)->resolve();
                        $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'] = [];
                    }

                    $activityModel = $searchedModel->attributesToArray();
                    $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'][$activityId] = SearchResource::make($activityModel)->resolve();
                }
            }
        }

        return $projects;
    }

    /**
     * Get the advance search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function advanceSearchForm($data)
    {
        $counts = [];
        $projectIds = [];

        if (isset($data['userIds']) && !empty($data['userIds'])) {
            $userIds = $data['userIds'];

            $projectIds = Project::whereHas('users', function (Builder $query) use($userIds) {
                $query->whereIn('id', $userIds);
            })->pluck('id')->toArray();
        }

        $searchResultQuery = $this->model->searchForm()
                            ->query(Arr::get($data, 'query', 0))
                            ->join(Project::class, Playlist::class)
                            ->aggregate('count_by_index', [
                                'terms' => [
                                    'field' => '_index',
                                ]
                            ])
                            ->isPublic(true)
                            ->elasticsearch(true)
                            ->type(Arr::get($data, 'type', 0))
                            ->subjectIds(Arr::get($data, 'subjectIds', []))
                            ->educationLevelIds(Arr::get($data, 'educationLevelIds', []))
                            ->projectIds($projectIds)
                            ->negativeQuery(Arr::get($data, 'negativeQuery', 0))
                            ->sort(Arr::get($data, 'sort', '_id'), Arr::get($data, 'order', 'desc'))
                            ->from(Arr::get($data, 'from', 0))
                            ->size(Arr::get($data, 'size', 10));

        if (isset($data['model']) && !empty($data['model'])) {
            $searchResultQuery = $searchResultQuery->postFilter('term', ['_index' => $data['model']]);
        }

        $searchResult = $searchResultQuery->execute();

        $aggregations = $searchResult->aggregations();
        $countByIndex = $aggregations->get('count_by_index');

        if (isset($countByIndex["buckets"])) {
            foreach ($countByIndex["buckets"] as $indexData) {
                $counts[$indexData["key"]] = $indexData["doc_count"];
            }
        }

        $counts["total"] = array_sum($counts);

        return (SearchResource::collection($searchResult->models()))
                ->additional(['meta' => $counts]);
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
     * @param Request $request
     * @param Playlist $playlist
     * @param Activity $activity
     * @return type
     */
    public function clone(Request $request, Playlist $playlist, Activity $activity)
    {
        $h5P_res = null;
        $token = $request->bearerToken();
        if (!empty($activity->h5p_content_id) && $activity->h5p_content_id != 0) {
            $h5P_res = $this->download_and_upload_h5p($token, $activity->h5p_content_id);
        }


        $new_thumb_url = config('app.default_thumb_url');
        if (Storage::disk('public')->exists('projects/' . basename($activity->thumb_url)) && is_file(storage_path("app/public/projects/" . basename($activity->thumb_url)))) {
            $ext = pathinfo(basename($activity->thumb_url), PATHINFO_EXTENSION);
            $new_image_name_mtd = uniqid() . '.' . $ext;
            ob_start();
            \File::copy(storage_path("app/public/projects/" . basename($activity->thumb_url)), storage_path("app/public/projects/" . $new_image_name_mtd));
            ob_get_clean();
            $new_thumb_url = "/storage/projects/" . $new_image_name_mtd;
        }
        $activity_data = [
            'title' => $activity->title,
            'type' => $activity->type,
            'content' => $activity->content,
            'playlist_id' => $playlist->id,
            'order' => $activity->order,
            'h5p_content_id' => $activity->h5p_content_id,
            'thumb_url' => $new_thumb_url,
            'subject_id' => $activity->subject_id,
            'education_level_id' => $activity->education_level_id,
        ];

        $cloned_activity = $this->activityRepository->create($activity_data);

        return $cloned_activity['id'];
    }
    
    /**
     * To export and import an H5p File
     * @param type $token
     * @param type $h5p_content_id
     * @return type
     */
    public function download_and_upload_h5p($token, $h5p_content_id)
    {
        $new_h5p_file = uniqid() . ".h5p";

        $response = null;
        try {
            $response = $this->client->request('GET', config('app.url') . '/api/h5p/export/' . $h5p_content_id, ['sink' => storage_path("app/public/uploads/" . $new_h5p_file), 'http_errors' => false]);
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
                            'contents' => fopen(storage_path("app/public/uploads/" . $new_h5p_file), 'r')
                        ]
                    ]
                ]); 
            } catch (Excecption $ex) {
                
            }
            
            if (!is_null($response_h5p) && $response_h5p->getStatusCode() == 200) {
                unlink(storage_path("app/public/uploads/" . $new_h5p_file));
                return json_decode($response_h5p->getBody()->getContents());
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

}
