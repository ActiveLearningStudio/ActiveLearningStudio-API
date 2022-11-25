<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    use SoftDeletes, GlobalScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'organization_id',
        'description',
        'indexing',
        'noovo_group_title'
    ];

    /**
     * Get the users for the team
     */
    public function users()
    {
        // return $this->belongsToMany('App\User', 'user_team')->withPivot('role')->withTimestamps();
        return $this->belongsToMany('App\User', 'team_user_roles')->using('App\Models\TeamUserRole')->withPivot('team_role_type_id')->withTimestamps();
    }

    /**
     * Get the invited users for the team
     */
    public function invitedUsers()
    {
        return $this->hasMany('App\Models\InvitedTeamUser', 'team_id');
    }

    /**
     * Cascade on delete the team
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Team $team) {
            foreach ($team->projects as $project) {
                $project->delete();
            }
        });
    }

    /**
     * Get the projects for the team
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'team_project')->withTimestamps();
    }

    /**
     * Get the organization of the team
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id');
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

    /**
     * The user roles that belong to the team.
     */
    public function userRoles()
    {
        return $this->belongsToMany('App\Models\TeamRoleType', 'team_user_roles');
    }
}
