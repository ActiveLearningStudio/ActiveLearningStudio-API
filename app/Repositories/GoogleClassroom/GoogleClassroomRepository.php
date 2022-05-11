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
            $data['user_id'] = isset($course->user_id) ? $course->user_id : Auth::id();
            $data['course_id'] = $course->id;
            $data['name'] = $course->name;
            $data['section'] = isset($course->section) ? $course->section : '';
            $data['description_heading'] = isset($course->descriptionHeading) ? $course->descriptionHeading : '';
            $data['description'] = isset($course->description) ? $course->description : '';
            $data['room'] = isset($course->room) ? $course->room : '';
            $data['owner_id'] = isset($course->ownerId) ? $course->ownerId : '';
            $data['enrollment_code'] = isset($course->enrollmentCode) ? $course->enrollmentCode : '';
            $data['course_state'] = isset($course->courseState) ? $course->courseState : 'COURSE_STATE_UNSPECIFIED';
            $data['alternate_link'] = isset($course->alternateLink) ? $course->alternateLink : '';
            $data['teacher_group_email'] = isset($course->teacherGroupEmail) ? $course->teacherGroupEmail : '';
            $data['course_group_email'] = isset($course->courseGroupEmail) ? $course->courseGroupEmail : '';
            $data['guardians_enabled'] = isset($course->guardiansEnabled) ? $course->guardiansEnabled : 0;
            $data['calendar_id'] = isset($course->calendarId) ? $course->calendarId : '';
            $data['curriki_teacher_email'] = isset($course->curriki_teacher_email) ? $course->curriki_teacher_email : auth()->user()->email;
            $data['curriki_teacher_org'] = isset($course->curriki_teacher_org) ? $course->curriki_teacher_org : '';

            if ($item = $this->model->create($data)) {
                return $item;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to add Google Classroom data into storage, please try again later!');
    }

    /**
     * Validate if Course teacher record already exist in DB or Not
     *
     * @param $courseId
     * @param $email
     * @return Response
     */
    public function duplicateRecordValidation($courseId, $email) {
        $response  = $this->model->where([
            ['course_id', '=', $courseId],
            ['curriki_teacher_email', '=', $email]
        ])->first();

        return $response;
    }

    /**
     * Get teacher_email to fetch the id of teacher/publisher 
     *
     * @param $glassAltCourseId
     * @return Response
     */
    public function fetchPublisherData($glassAltCourseId)
    {
        return $response  = $this->model->where('alternate_link', $glassAltCourseId)->with('publisherUser.publisherOrg')->first();
    }
}
