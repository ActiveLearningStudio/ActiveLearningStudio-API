<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Commands\GetCourseDetailsCommand;
use App\CurrikiGo\Canvas\Commands\GetUserCommand;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;

/**
 * SaveTeacherData class for saving Teacher's data who publishing course/assignment to Canvas LMS
 */
class SaveTeacherData
{
    /**
     * Canvas Client instance
     *
     * @var \App\CurrikiGo\Canvas\Client
     */
    private $canvasClient;

    /**
     * Make an instance of the class
     *
     * @param \App\CurrikiGo\Canvas\Client $client
     */
    public function __construct(Client $client)
    {
        $this->canvasClient = $client;
    }

    /**
     * Pull Course detail and teacher data from Canvas LMS
     *
     * @param array $data
     * @param \App\Repositories\GoogleClassroom\GoogleClassroomRepository
     * @return array
     */
    public function saveData($data, GoogleClassroomRepositoryInterface $googleClassroomRepository)
    {
        $courseData = $this->canvasClient->run(new GetCourseDetailsCommand($data->courseId, '?include[]=teachers'));

        if ($courseData) {
            foreach ($courseData->teachers as $teacher) {
                $teacherInfo = new \stdClass();
                $record = $this->canvasClient->run(new GetUserCommand($teacher->id));
                $teacherInfo->user_id = 1;
                $teacherInfo->id = $data->courseId;
                $teacherInfo->name = $courseData->name;
                $teacherInfo->curriki_teacher_email = $record->email;
                $googleClassroomData = $googleClassroomRepository->saveCourseShareToGcClass($teacherInfo);
            }
            return response(
                ['message' => "Teacher's data saved successfuly!"],
                201
            );
        }
        return response(
            ['message' => "No course found!"], 404
        );
    }
}
