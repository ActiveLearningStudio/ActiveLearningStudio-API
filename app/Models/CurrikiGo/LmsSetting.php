<?php

namespace App\Models\CurrikiGo;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class LmsSetting extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lms_url',
        'lms_access_token',
        'site_name',
        'lms_name',
        'lms_access_key',
        'lms_access_secret',
        'description',
        'lti_client_id',
        'user_id'
    ];

    /**
     * Get the user that owns the activity
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
