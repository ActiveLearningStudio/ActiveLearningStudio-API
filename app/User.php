<?php

namespace App;

use App\Notifications\MailResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable, SoftDeletes;

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
        'password',
        'temp_password',
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
     * Get the projects for the user
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'user_project')->withPivot('role')->withTimestamps();
    }

    public function lmssetting()
    {
        return $this->hasOne('App\Models\CurrikiGo\LmsSetting');
    }

    public function membership()
    {
        return $this->belongsTo('App\Models\MembershipType', 'membership_type_id');
    }

    /**
     * The organisations that belong to the user.
     */
    public function organisations()
    {
        return $this->belongsToMany('App\Models\Organisation', 'organisation_user_roles')->withPivot('organisation_role_type_id')->withTimestamps();
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
        $this->notify(new VerifyEmail);
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
     * @param $query
     * @param $value
     * @return mixed
     * Scope for combine first and last name search
     */
    public function scopeName($query, $value)
    {
        return $query->orWhereRaw("CONCAT(first_name,' ',last_name) ILIKE '%" . $value . "%'");
    }

    /**
     * @param $query
     * @param $columns
     * @param $value
     * @return mixed
     * Scope for searching in specific columns
     */
    public function scopeSearch($query, $columns, $value)
    {
        foreach ($columns as $column) {
            // no need to perform search if searchable is false
            if (isset($column['searchable']) && $column['searchable'] === 'false') {
                continue;
            }
            $column = $column['name'] ?? $column;
            $query->orWhere($column, 'ILIKE', '%' . $value . '%');
        }
        return $query;
    }
}
