<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\CustomSearch;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use App\Models\QueryBuilders\SearchFormQueryBuilder;
use App\Repositories\Activity\ActivityRepositoryInterface;

class Activity extends Model
{
    use SoftDeletes, Searchable, CustomSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'playlist_id',
        'title',
        'type',
        'content',
        'shared',
        'order',
        'thumb_url',
        'subject_id',
        'education_level_id',
        'h5p_content_id',
        'indexing'
    ];

    /**
     * Get the attributes to be indexed in Elasticsearch
     */
    public function toSearchableArray()
    {
        $searchableArray = [
            'h5p_library' => $this->h5pLibrary,
            'playlist_id' => $this->playlist_id,
            'title' => $this->title,
            'type' => $this->type,
            'content' => $this->content,
            'h5p_content_id' => $this->h5p_content_id,
            'subject_id' => $this->subject_id,
            'education_level_id' => $this->education_level_id,
            'created_at' => $this->created_at ? $this->created_at->toAtomString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toAtomString() : null
        ];

        if ($this->playlist) {
            $searchableArray['project_id'] = $this->playlist->project_id;

            if ($this->playlist->project) {
                $searchableArray['indexing'] = $this->playlist->project->indexing;
                $searchableArray['organization_id'] = $this->playlist->project->organization_id;
                $searchableArray['organization_visibility_type_id'] = $this->playlist->project->organization_visibility_type_id;
            }
        }

        $activityRepository = resolve(ActivityRepositoryInterface::class);
        $searchableArray = $searchableArray + $activityRepository->getH5pElasticsearchFields($this->h5p_content);

        return $searchableArray;
    }

    /**
     * Get the search request
     */
    public static function searchForm(): SearchRequestBuilder
    {
        return new SearchRequestBuilder(new static(), new SearchFormQueryBuilder());
    }

    /**
     * Get the playlist that owns the activity
     */
    public function playlist()
    {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id');
    }

    /**
     * Get the activity's project's user.
     *
     * @return object
     */
    public function getUserAttribute()
    {
        if (isset($this->playlist) && isset($this->playlist->project) && isset($this->playlist->project->users)) {
            return $this->playlist->project->users()->wherePivot('role', 'owner')->first();
        }

        return null;
    }

    /**
     * Get the model type.
     *
     * @return string
     */
    public function getModelTypeAttribute()
    {
        return 'Activity';
    }

    /**
    * Get the H5P Content relation
    */
    public function h5p_content()
    {
        return $this->belongsTo('Djoudi\LaravelH5p\Eloquents\H5pContent', 'h5p_content_id');
    }

    public function metrics()
    {
        return $this->hasOne('App\Models\ActivityMetric');
    }

    /**
     * Get the h5p library.
     *
     * @return string
     */
    public function getH5pLibraryAttribute()
    {
        $h5pLibrary = null;

        if ($this->h5p_content && $this->h5p_content->library) {
            $h5pLibrary = $this->h5p_content->library->name . ' ' .$this->h5p_content->library->major_version . '.' .$this->h5p_content->library->minor_version;
        }

        return $h5pLibrary;
    }
}
