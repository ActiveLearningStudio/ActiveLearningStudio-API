<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use ElasticScoutDriverPlus\CustomSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Team extends Model
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
        'indexing',
    ];

    /**
     * Get the users for the team
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_team')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the projects for the team
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'team_project')->withTimestamps();
    }

    /**
     * Get the team's owner.
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
     * Get the attributes to be indexed in Elasticsearch
     */
    public function toSearchableArray()
    {
        $searchable = [
            'team_id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'indexing' => $this->indexing,
            'created_at' => $this->created_at ? $this->created_at->toAtomString() : '',
            'updated_at' => $this->updated_at ? $this->updated_at->toAtomString() : ''
        ];

        return $searchable;
    }

    /**
     * Maps the indexing integer value and returns the text
     * @return string|null
     */
    public function getIndexingTextAttribute(){
        return self::$indexing[$this->indexing] ?? 'NOT REQUESTED';
    }

    /**
     * Get the model type.
     *
     * @return string
     */
    public function getModelTypeAttribute()
    {
        return 'Team';
    }
}
