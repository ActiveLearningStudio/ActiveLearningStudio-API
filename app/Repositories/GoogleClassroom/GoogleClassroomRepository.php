<?php

namespace App\Repositories\GoogleClassroom;

use App\Exceptions\GeneralException;
use App\Models\GoogleClassroom;
use App\Repositories\BaseRepository;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class GoogleClassroomRepository extends BaseRepository implements GoogleClassroomRepositoryInterface
{
    /**
     * GoogleClassroomRepository constructor.
     *
     * @param GoogleClassroom $model
     */
    public function __construct(GoogleClassroom $model)
    {
        parent::__construct($model);
    }

    /**
     * Save course share to Google classroom
     *
     * @param $course
     * @return Response
     * @throws GeneralException
     */
    public function saveCourseShareToGcClass($course)
    {
        $data = array();
        try {
            $data['user_id'] = Auth::id();
            $data['course_id'] = $course->id;
            $data['name'] = $course->name;
            $data['section'] = $course->section;
            $data['description_heading'] = $course->descriptionHeading;
            $data['description'] = $course->description;
            $data['room'] = $course->room;
            $data['owner_id'] = $course->ownerId;
            $data['enrollment_code'] = $course->enrollmentCode;
            $data['course_state'] = $course->courseState;
            $data['alternate_link'] = $course->alternateLink;
            $data['teacher_group_email'] = $course->teacherGroupEmail;
            $data['course_group_email'] = $course->courseGroupEmail;
            $data['guardians_enabled'] = $course->guardiansEnabled;
            $data['calendar_id'] = $course->calendarId;
            $data['curriki_teacher_email'] = auth()->user()->email;

            if ($item = $this->model->create($data)) {
                return $item;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to add Google Classroom data into storage, please try again later!');
    }
}
