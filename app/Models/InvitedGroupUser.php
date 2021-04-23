<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitedGroupUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invited_email',
        'group_id',
        'token',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invited_group_users';

    /**
     * Scope for email search
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearchByEmail($query, $value)
    {
        return $query->orWhereRaw("invited_email = '" . $value . "'");
    }

}
