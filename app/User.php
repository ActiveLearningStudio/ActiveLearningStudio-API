<?php

namespace App;

use App\Models\DeepRelations\HasManyDeep;
use App\Models\DeepRelations\HasRelationships;
use App\Models\Traits\GlobalScope;
use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use App\Repositories\User\UserRepositoryInterface;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes, GlobalScope, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'role',
        'password',
        'remember_token',
        'organization_name',
        'organization_type',
        'website',
        'job_title',
        'address',
        'phone_number',
        'hubspot',
        'subscribed',
        'subscribed_ip',
        'email_verified_at',
        'membership_type_id',
        'gapi_access_token',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param $password
     */
    public function setPasswordAttribute($password): void
    {
        // If password was accidentally passed in already hashed, try not to double hash it
        if (
            (\strlen($password) === 60 && preg_match('/^\$2y\$/', $password)) ||
            (\strlen($password) === 95 && preg_match('/^\$argon2i\$/', $password))
        ) {
            $hash = $password;
        } else {
            $hash = Hash::make($password);
        }

        $this->attributes['password'] = $hash;
    }

    /**
     * Combine first and last name for Name column
     * @param $name
     */
    public function setNameAttribute($name): void
    {
        $this->attributes['name'] = $this->attributes['first_name'] . ' ' . $this->attributes['last_name'];
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getNameAttribute($name)
    {
        if (!$name || empty($name)) {
            return "{$this->first_name} {$this->last_name}";
        }
        return $name;
    }

    /**
     * Get the teams for the user
     */
    public function teams()
    {
        // return $this->belongsToMany('App\Models\Team', 'user_team')->withPivot('role')->withTimestamps();
        return $this->belongsToMany('App\Models\Team', 'team_user_roles')->using('App\Models\TeamUserRole')->withPivot('team_role_type_id')->withTimestamps();
    }

    /**
     * Get the groups for the user
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'user_group')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the projects for the user
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'user_project')
            ->withPivot('role')
            ->orderBy('order','asc')
            ->withTimestamps();
    }

    /**
     * Get playlists directly from users model via hasManyThrough
     * @return HasManyThrough
     */
    public function playlists()
    {
        return $this->hasManyThrough(
            'App\Models\Playlist',
            'App\Models\Pivots\UserProject',
            'user_id',
            'project_id',
            'id',
            'project_id'
        );
    }

    /**
     * Get far away relations data using custom Deep classes
     * @return HasManyDeep
     */
    public function activities()
    {
        return $this->hasManyDeep(
            'App\Models\Activity',
            ['App\Models\Pivots\UserProject', 'App\Models\Playlist'], // Intermediate models, beginning at the far parent (Users).
            [
                'user_id',      // Foreign key on the "user_project" table.
                'project_id',   // Foreign key on the "playlist" table.
                'playlist_id'   // Foreign key on the "activity" table.
            ],
            [
                'id',           // Local key on the "users" table.
                'project_id',   // Local key on the "user_project" table.
                'id'            // Local key on the "playlist" table.
            ]
        );
    }

    public function lmssetting()
    {
        return $this->hasMany('App\Models\CurrikiGo\LmsSetting');
    }

    public function membership()
    {
        return $this->belongsTo('App\Models\MembershipType', 'membership_type_id');
    }

    /**
     * The organizations that belong to the user.
     */
    public function organizations()
    {
        return $this->belongsToMany('App\Models\Organization', 'organization_user_roles')->using('App\Models\OrganizationUserRole')->withPivot('organization_role_type_id')->withTimestamps();
    }

    /**
     * The SSO Logins that belongs to the user
     */
    public function ssoLogin()
    {
        return $this->hasMany('App\Models\SsoLogin', 'user_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cascade on delete the user
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (User $user) {
            foreach ($user->projects as $project) {
                $project->delete();
            }
        });
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailNotification);
    }

    /**
     * Send Password Reset notification
     *
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordNotification($token));
    }

    /**
     * Scope for combine first and last name search
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeName($query, $value)
    {
        return $query->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE '%" . $value . "%'");
    }

    /**
     * Scope for email search
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearchByEmailAndName($query, $value)
    {
        return $query->orWhereRaw("first_name ILIKE '" . $value . "%'")
                     ->orWhereRaw("email ILIKE '" . $value . "%'")
                     ->orWhereRaw("CONCAT(first_name, ' ', last_name) ILIKE '" . $value . "%'");
    }

    /**
     * Get the favorite projects for the user
     */
    public function favoriteProjects()
    {
        return $this->belongsToMany('App\Models\Project', 'user_favorite_project')->withTimestamps();
    }

    /**
     * Check if user has the specified permission in the provided organization
     *
     * @param $permission
     * @param $organization
     * @return boolean
     */
    public function hasPermissionTo($permission, $organization)
    {
        $userRepository = resolve(UserRepositoryInterface::class);
        return $userRepository->hasPermissionTo($this, $permission, $organization);
    }

    /**
     * Check if user has the specified permission in the provided team role
     *
     * @param $permission
     * @param $team
     * @return boolean
     */
    public function hasTeamPermissionTo($permission, $team)
    {
        $userRepository = resolve(UserRepositoryInterface::class);
        return $userRepository->hasTeamPermissionTo($this, $permission, $team);
    }
}
