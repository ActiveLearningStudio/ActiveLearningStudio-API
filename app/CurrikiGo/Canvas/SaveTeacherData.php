<?php

namespace App\CurrikiGo\Canvas;

use App\CurrikiGo\Canvas\Commands\GetCourseDetailsCommand;
use App\CurrikiGo\Canvas\Commands\GetUserCommand;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;

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
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @param UserRepositoryInterface $userRepository
     * @return array
     */
    public function saveData($data, GoogleClassroomRepositoryInterface $googleClassroomRepository, UserRepositoryInterface $userRepository)
    {
        $courseData = $this->canvasClient->run(new GetCourseDetailsCommand($data->courseId, '?include[]=teachers'));

        if ($courseData) {
            $response = [];
            foreach ($courseData->teachers as $teacher) {
                $record = $this->canvasClient->run(new GetUserCommand($teacher->id));
                $duplicateRecord = $googleClassroomRepository->duplicateRecordValidation($data->courseId, $record->email);
                $userExists = $userRepository->findByField('email', $record->email);
                if (!$userExists) {
                    $userExists = $userRepository->getFirstUser();
                }
                if (!$duplicateRecord) {
                    $teacherInfo = new \stdClass();
                    $teacherInfo->user_id = $userExists->id;
                    $teacherInfo->id = $data->courseId;
                    $teacherInfo->name = $courseData->name;
                    $teacherInfo->alternateLink = $data->customApiDomainUrl . '/' . $data->courseId;
                    $teacherInfo->curriki_teacher_email = $record->email;
                    $teacherInfo->curriki_teacher_org = $data->organizarion_from_lmsSettings;
                    $response[] = $googleClassroomRepository->saveCourseShareToGcClass($teacherInfo);
                }
            }
            if ($response) {
                return response(
                    ['message' => "Teacher's data saved successfuly!"], 201
                );
            }
            return response(
                ['message' => "Record already exist!"], 200
            );

        }
        return response(
            ['message' => "No course found!"], 404
        );
    }
}
