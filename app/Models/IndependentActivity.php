<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndependentActivity extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'activities';
    protected $fillable = [
        'title',
        'type',
        'content',
        'shared',
        'order',
        'thumb_url',
        'subject_id',
        'education_level_id',
        'h5p_content_id',
        'indexing',
        'user_id',
        'organization_id',
        'description',
        'source_type',
        'source_url',
        'activity_type',
        'organization_visibility_type_id',
        'cloned_from',
        'clone_ctr',
        'status',
        'indexing',
        'original_user'
    ];

    /**
     * STATIC PROPERTIES FOR MAPPING THE DATABASE COLUMN VALUES
     */
    public static $status = [1 => 'DRAFT' , 2 => 'FINISHED'];
    public static $indexing = [1 => 'REQUESTED', 2 => 'NOT APPROVED', 3 => 'APPROVED'];

    /**
     * Cascade on delete the IndependentActivity
     */
    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('approve', function (Builder $builder) {
            $builder->where('activity_type', config('constants.activity_type.independent'));
        });

        self::creating(function(IndependentActivity $activity) {
            $activity->activity_type = config('constants.activity_type.independent');
        });

        self::deleting(function (IndependentActivity $independentActivity) {
            H5pBrightCoveVideoContents::where('h5p_content_id', $independentActivity->h5p_content_id)->delete();
            $independentActivity->subjects()->detach();
            $independentActivity->educationLevels()->detach();
            $independentActivity->authorTags()->detach();
        });
    }

    /**
     * Get the user that owns the independent activity
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    /**
     * Get the model type.
     *
     * @return string
     */
    public function getModelTypeAttribute()
    {
        return 'IndependentActivity';
    }

    /**
    * Get the H5P Content relation
    */
    public function h5p_content()
    {
        return $this->belongsTo('Djoudi\LaravelH5p\Eloquents\H5pContent', 'h5p_content_id');
    }

    // public function metrics()
    // {
    //     return $this->hasOne('App\Models\ActivityMetric');
    // }

    /**
     * Get the h5p library.
     *
     * @return string
     */
    public function getH5pLibraryAttribute()
    {
        $h5pLibrary = null;

        if ($this->h5p_content && $this->h5p_content->library) {
            $h5pLibrary = $this->h5p_content->library->name . ' ' .$this->h5p_content->library->major_version . '.' .$this->h5p_content->library->minor_version;
        }

        return $h5pLibrary;
    }

    /**
     * Get the independent activity subjects.
     */
    public function subjects()
    {
        return $this->belongsToMany('App\Models\Subject', 'activity_subject', 'activity_id')->withTimestamps();
    }

    /**
     * Get the independent activity education levels.
     */
    public function educationLevels()
    {
        return $this->belongsToMany('App\Models\EducationLevel', 'activity_education_level', 'activity_id')->withTimestamps();
    }

    /**
     * Get the independent activity author tags.
     */
    public function authorTags()
    {
        return $this->belongsToMany('App\Models\AuthorTag', 'activity_author_tag', 'activity_id')->withTimestamps();
    }

    /**
     * Get the organization that owns the independent activity.
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Maps the indexing integer value and returns the text
     * @return string|null
     */
    public function getIndexingTextAttribute()
    {
        return self::$indexing[$this->indexing] ?? 'NOT REQUESTED';
    }

    /**
     * Maps the status value and returns the text
     * @return string|null
     */
    public function getStatusTextAttribute()
    {
        return self::$status[$this->status] ?? null;
    }
}
