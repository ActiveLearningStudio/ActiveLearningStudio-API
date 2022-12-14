<?php

namespace App\Models;

use Illuminate\Http\Request;
use App\Models\Traits\GlobalScope;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use SoftDeletes, GlobalScope, HasFactory;

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
        'organization_id',
        'organization_visibility_type_id',
        'team_id'
    ];

    /**
     * STATIC PROPERTIES FOR MAPPING THE DATABASE COLUMN VALUES
     */
    public static $status = [1 => 'DRAFT' , 2 => 'FINISHED'];
    public static $indexing = [1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'];

    /**
     * Get the users for the project
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_project')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the team of the project
     */
    public function team()
    {
        // return $this->belongsToMany('App\Models\Team', 'team_project')->withTimestamps();
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * Get the playlists for the project
     */
    public function playlists()
    {
        return $this->hasMany('App\Models\Playlist', 'project_id');
    }

    /**
     * Get the single playlist for the project
     */
    public function singlePlaylist()
    {
        return $this->hasOne('App\Models\Playlist', 'project_id');
    }

    /**
     * Get the organization that owns the project.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Get the Organization visibility type for the project (private, protected, global, public)
     */
    public function organizationVisibilityType()
    {
        return $this->belongsTo('App\Models\OrganizationVisibilityType', 'organization_visibility_type_id');
    }

    /**
     * Cascade on delete the project
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Project $project) {
            $isForceDeleting = $project->isForceDeleting();

            if ($isForceDeleting && File::exists(public_path($project->thumb_url))) {
                File::delete(public_path($project->thumb_url));
            }

            foreach ($project->playlists as $playlist) {
                if ($isForceDeleting) {
                    $playlist->forceDelete();
                } else {
                    $playlist->delete();
                }
            }
        });
    }

    /**
     * Get the project's owner.
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
        return self::$indexing[$this->indexing] ?? 'NOT REQUESTED';
    }

    /**
     * Maps the status value and returns the text
     * @return string|null
     */
    public function getStatusTextAttribute(){
        return self::$status[$this->status] ?? null;
    }

    /**
     * The users that favored the project.
     */
    public function favoredByUsers()
    {
        return $this->belongsToMany('App\User', 'user_favorite_project')->withTimestamps();
    }

    /**
     * Get the favored status.
     *
     * @return string
     */
    public function getFavoredAttribute()
    {
        $user = auth()->user();
        if ($user) {
            return $this->favoredByUsers->find(auth()->user()->id) ? true : false;
        }
        return false;
    }
}
