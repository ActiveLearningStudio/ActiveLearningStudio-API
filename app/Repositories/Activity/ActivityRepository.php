<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\Activity\ActivityRepositoryInterface;
use App\Repositories\BaseRepository;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Http\Resources\V1\SearchResource;
use App\Repositories\User\UserRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;
    private $client;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     * @param H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository
     */
    public function __construct(Activity $model, H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository)
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
            ->indexing([3])
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
     * Get the advance search request
     *
     * @param array $data
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function advanceSearchForm($data)
    {
        $counts = [];
        $projectIds = [];

        if (isset($data['userIds']) && !empty($data['userIds'])) {
            $userIds = $data['userIds'];

            $projectIds = Project::whereHas('users', function (Builder $query) use ($userIds) {
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
            ->type(Arr::get($data, 'type', 0))
            ->startDate(Arr::get($data, 'startDate', 0))
            ->endDate(Arr::get($data, 'endDate', 0))
            ->indexing(Arr::get($data, 'indexing', []))
            ->subjectIds(Arr::get($data, 'subjectIds', []))
            ->educationLevelIds(Arr::get($data, 'educationLevelIds', []))
            ->projectIds($projectIds)
            ->h5pLibraries(Arr::get($data, 'h5pLibraries', []))
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

        if (isset($countByIndex['buckets'])) {
            foreach ($countByIndex['buckets'] as $indexData) {
                $counts[$indexData['key']] = $indexData['doc_count'];
            }
        }

        $counts['total'] = array_sum($counts);

        return (SearchResource::collection($searchResult->models()))->additional(['meta' => $counts]);
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
        return ($playlist->project->indexing === 3) ? $playlist : false;
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
}
