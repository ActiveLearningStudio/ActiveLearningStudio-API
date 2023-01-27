<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GoogleClassroom extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gclass_api_data';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'course_id',
        'name',
        'section',
        'description_heading',
        'description',
        'room',
        'owner_id',
        'enrollment_code',
        'course_state',
        'alternate_link',
        'teacher_group_email',
        'course_group_email',
        'guardians_enabled',
        'calendar_id',
        'curriki_teacher_email',
        'curriki_teacher_org'
    ];

    /**
     * Cascade on delete the user
     */
    public static function boot()
    {
        parent::boot();

        self::retrieved(function (GoogleClassroom $gclassData) {
            $gclassData->curriki_teacher_email = strtolower($gclassData->curriki_teacher_email);
        });

        self::creating(function(GoogleClassroom $gclassData) {
            $gclassData->curriki_teacher_email = strtolower($gclassData->curriki_teacher_email);
        });
    }
    
    /**
     * Get the user_id for the teacher
     */
    public function publisherUser()
    {
        return $this->hasOne(User::class, 'email' , 'curriki_teacher_email');
    }

}
