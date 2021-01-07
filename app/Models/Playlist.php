<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\CustomSearch;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use App\Models\QueryBuilders\SearchFormQueryBuilder;

class Playlist extends Model
{
    use SoftDeletes, Searchable, CustomSearch;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'project_id',
        'order'
    ];

    /**
     * Get the attributes to be indexed in Elasticsearch
     */
    public function toSearchableArray()
    {
        $searchableArray = [
            'title' => $this->title,
            'project_id' => $this->project_id,
            'created_at' => $this->created_at ? $this->created_at->toAtomString() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toAtomString() : null
        ];

        if ($this->project) {
            $searchableArray['indexing'] = $this->project->indexing;
            $searchableArray['organization_id'] = $this->project->organization_id;
            $searchableArray['organization_visibility_type_id'] = $this->project->organization_visibility_type_id;
        }

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
     * Get the project that owns the playlist
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }

    /**
     * Get the activities for the playlist
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity', 'playlist_id');
    }

    /**
     * Cascade on delete the playlist
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Playlist $playlist) {
            foreach ($playlist->activities as $activity)
            {
                $activity->delete();
            }
        });
    }

    /**
     * Get the playlists's project's user.
     *
     * @return object
     */
    public function getUserAttribute()
    {
        if (isset($this->project) && isset($this->project->users)) {
            return $this->project->users()->wherePivot('role', 'owner')->first();
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
        return 'Playlist';
    }

    /**
     * Get the playlists's project's thumb_url.
     *
     * @return object
     */
    public function getThumbUrlAttribute()
    {
        if (isset($this->project) && isset($this->project->thumb_url)) {
            return $this->project->thumb_url;
        }

        return null;
    }
}
