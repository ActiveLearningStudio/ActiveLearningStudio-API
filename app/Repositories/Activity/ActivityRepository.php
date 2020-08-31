<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Project;
use App\Models\Playlist;
use Illuminate\Support\Arr;
use App\Repositories\H5pElasticsearchField\H5pElasticsearchFieldRepositoryInterface;
use App\Http\Resources\V1\SearchResource;
use Illuminate\Database\Eloquent\Builder;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    private $h5pElasticsearchFieldRepository;

    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     * @param H5pElasticsearchFieldRepositoryInterface $activityRepository
     */
    public function __construct(Activity $model, H5pElasticsearchFieldRepositoryInterface $h5pElasticsearchFieldRepository)
    {
        parent::__construct($model);
        $this->h5pElasticsearchFieldRepository = $h5pElasticsearchFieldRepository;
    }

    /**
     * Get the search request
     *
     * @param  Request  $request
     * @return Collection
     */
    public function searchForm($request)
    {
        $projects = [];

        $searchedModels = Activity::searchForm()
                            ->query($request->input('query', 0))
                            ->join(Project::class, Playlist::class)
                            ->shared(true)
                            ->sort($request->input('sort', '_id'), $request->input('order', 'desc'))
                            ->from($request->input('from', 0))
                            ->size($request->input('size', 10))
                            ->execute()
                            ->models();

        foreach ($searchedModels as $modelId => $searchedModel) {
            $modelName = class_basename($searchedModel);

            if ($modelName == 'Project' && !isset($projects[$searchedModel->id])) {
                $projects[$searchedModel->id] = $searchedModel->attributesToArray();
                $projects[$searchedModel->id]['playlists'] = [];
            } else if ($modelName == 'Playlist' && !isset($projects[$searchedModel->project_id]['playlists'][$searchedModel->id])) {
                $playlistProjectid = $searchedModel->project_id;

                if (!isset($projects[$playlistProjectid])) {
                    $projects[$playlistProjectid] = $searchedModel->project->attributesToArray();
                    $projects[$playlistProjectid]['playlists'] = [];
                }

                $projects[$playlistProjectid]['playlists'][$searchedModel->id] = $searchedModel->attributesToArray();
                $projects[$playlistProjectid]['playlists'][$searchedModel->id]['activities'] = [];
            } else if ($modelName == 'Activity') {
                $activityId = $searchedModel->id;
                $activityPlaylist = $searchedModel->playlist;
                $activityPlaylistId = $activityPlaylist->id;
                $activityPlaylistProjectid = $activityPlaylist->project_id;

                if (!isset($projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'][$activityId])) {
                    if (!isset($projects[$activityPlaylistProjectid])) {
                        $projects[$activityPlaylistProjectid] = $activityPlaylist->project->attributesToArray();
                        $projects[$activityPlaylistProjectid]['playlists'] = [];
                    }

                    if (!isset($projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId])) {
                        $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId] = $activityPlaylist->attributesToArray();
                        $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'] = [];
                    }

                    $activityModel = $searchedModel->attributesToArray();
                    $projects[$activityPlaylistProjectid]['playlists'][$activityPlaylistId]['activities'][$activityId] = $activityModel;
                }
            }
        }

        return $projects;
    }

    /**
     * Get the advance search request
     *
     * @param  Request  $request
     * @return Collection
     */
    public function advanceSearchForm($request)
    {
        $counts = [];
        $projectIds = [];

        if ($request->has('userIds') && $request->filled('userIds')) {
            $userIds = $request->input('userIds');

            $projectIds = Project::whereHas('users', function (Builder $query) use($userIds) {
                $query->whereIn('id', $userIds);
            })->pluck('id')->toArray();
        }

        $searchResultQuery = Activity::searchForm()
                            ->query($request->input('query', 0))
                            ->join(Project::class, Playlist::class)
                            ->aggregate('count_by_index', [
                                'terms' => [
                                    'field' => '_index',
                                ]
                            ])
                            ->shared(true)
                            ->type($request->input('type', 0))
                            ->subjectIds($request->input('subjectIds', []))
                            ->educationLevelIds($request->input('educationLevelIds', []))
                            ->projectIds($projectIds)
                            ->negativeQuery($request->input('negativeQuery', 0))
                            ->sort($request->input('sort', '_id'), $request->input('order', 'desc'))
                            ->from($request->input('from', 0))
                            ->size($request->input('size', 10));

        if ($request->has('model') && $request->filled('model')) {
            $searchResultQuery = $searchResultQuery->postFilter('term', ['_index' => $request->input('model')]);
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

        if ($h5pContent['parameters']) {
            $parameters = json_decode($h5pContent['parameters'], true);

            $h5pElasticsearchFields = $this->h5pElasticsearchFieldRepository->model->where([
                ['library_id', '=', $h5pContent['library_id']],
                ['indexed', '=', true],
            ])->get();

            foreach ($h5pElasticsearchFields as $h5pElasticsearchField) {
                $h5pElasticsearchFieldArray = explode('.group.nested.array.', $h5pElasticsearchField->path);

                if (count($h5pElasticsearchFieldArray) > 1) {
                    $h5pElasticsearchFieldValueArrays = Arr::get($parameters, $h5pElasticsearchFieldArray[0]);

                    if (is_array($h5pElasticsearchFieldValueArrays)){
                        foreach ($h5pElasticsearchFieldValueArrays as $h5pElasticsearchFieldValueArray) {
                            if (isset($h5pElasticsearchFieldValueArray[$h5pElasticsearchFieldArray[1]])) {
                                $h5pElasticsearchFieldValue = $h5pElasticsearchFieldValueArray[$h5pElasticsearchFieldArray[1]];
                                $h5pElasticsearchFieldsArray[$h5pElasticsearchField->path][] = $h5pElasticsearchFieldValue;
                            }
                        }
                    }
                } else {
                    $h5pElasticsearchFieldValue = Arr::get($parameters, $h5pElasticsearchField->path, null);

                    if ($h5pElasticsearchFieldValue !== null)
                        $h5pElasticsearchFieldsArray[$h5pElasticsearchField->path] = $h5pElasticsearchFieldValue;
                }
            }
        }

        return $h5pElasticsearchFieldsArray;
    }
}
