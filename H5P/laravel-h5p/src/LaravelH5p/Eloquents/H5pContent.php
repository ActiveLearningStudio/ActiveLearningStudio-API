<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class H5pContent extends Model
{
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'title',
        'library_id',
        'parameters',
        'filtered',
        'slug',
        'embed_type',
        'disable',
        'content_type',
        'author',
        'license',
        'keywords',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'user_id');
    }

    public function get_user()
    {
        return (object) DB::table('users')->where('id', $this->user_id)->first();
    }

    public function activity()
    {
        return $this->hasOne('App\Models\Activity', 'h5p_content_id');
    }

    public function library()
    {
        return $this->belongsTo(H5pLibrary::class, 'library_id');
    }

}
