<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use ElasticScoutDriverPlus\CustomSearch;
use ElasticScoutDriverPlus\Builders\SearchRequestBuilder;
use App\Models\QueryBuilders\SearchFormQueryBuilder;

class Project extends Model
{
    use SoftDeletes, Searchable, CustomSearch, GlobalScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'thumb_url',
        'shared',
        'starter_project',
        'is_user_starter',
        'indexing',
        'cloned_from',
        'clone_ctr',
        'order',
        'status',
    ];

    /**
     * STATIC PROPERTIES FOR MAPPING THE DATABASE COLUMN VALUES
     */
    public static $status = [1 => 'DRAFT' , 2 => 'FINISHED'];
    public static $indexing = [1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'];

    /**
     * Get the attributes to be indexed in Elasticsearch
     */
    public function toSearchableArray()
    {
        $searchableArray = [
            'project_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'elasticsearch' => $this->elasticsearch,
            'created_at' => $this->created_at ? $this->created_at->toAtomString() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toAtomString() : ''
        ];

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
     * Get the users for the projects
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_project')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the playlists for the project
     */
    public function playlists()
    {
        return $this->hasMany('App\Models\Playlist', 'project_id');
    }

    /**
     * Cascade on delete the project
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Project $project) {
            foreach ($project->playlists as $playlist)
            {
                $playlist->delete();
            }
        });
    }

    /**
     * Get the activity's project's user.
     *
     * @return object
     */
    public function getUserAttribute()
    {
        if (isset($this->users)) {
            return $this->users()->wherePivot('role', 'owner')->first();
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
        return 'Project';
    }

    /**
     * Maps the indexing integer value and returns the text
     * @return string|null
     */
    public function getIndexingTextAttribute(){
        return self::$indexing[$this->indexing] ?? null;
    }

    /**
     * Maps the status value and returns the text
     * @return string|null
     */
    public function getStatusTextAttribute(){
        return self::$status[$this->status] ?? null;
    }
}
