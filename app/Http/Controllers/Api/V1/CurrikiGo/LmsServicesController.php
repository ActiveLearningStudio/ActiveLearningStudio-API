<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetCourseDetailsCommand;
use App\CurrikiGo\Canvas\SaveTeacherData as SaveTeacherData;
use App\Http\Controllers\Controller;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use App\Models\CurrikiGo\LmsSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;
use App\Services\SaveStudentdataService;
use Validator;

class LmsServicesController extends Controller
{
    private $lmsSettingRepository;

    /**
     * Canvas Client instance
     *
     * @var \App\CurrikiGo\Canvas\Client
     */
    private $canvasClient;

    /**
     * LmsServicesController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     * @param LMSIntegrationServiceInterface $lms,
     * @param Client $canvasClient
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, LMSIntegrationServiceInterface $lms, Client $canvasClient)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->lms = $lms;
        $this->canvasClient = $canvasClient;
    }

    /**
     * Login to Canvas
     * 
     * Login to Canvas LMS
     * 
     * @urlParam lms required object of lms example: {"id": 1}
     *
     * @param Request $request
     * @param array $lms
     * 
     * @return Response
     */
    public function login(Request $request, $lms) {
        $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'lmsName' => 'required|string|max:255',
            'lmsUrl' => 'required|string|max:255|exists:lms_settings,lms_url',
            'lmsClientId' => 'required|exists:lms_settings,lti_client_id',
            'courseId' => 'required|string|max:255',
            'activityId' => 'required|integer',
        ]);

        return $this->lms->doLogin(
            $lms,
            LmsSetting::where('lms_url', urldecode($request->lmsUrl))->where('lti_client_id', $request->lmsClientId)->first(),
            ['username' => $request->username, 'password' => $request->password]
        );
    }

    /**
     * XApi File
     * 
     * Download XApi File
     * 
     * @urlParam activity required Id of an activity Example: 1
     *
     * @param Request $request
     * @param Activity $activity
     * 
     * @return download file
     */
    public function getXAPIFile(Request $request, Activity $activity) {
        return Storage::download($this->lms->getXAPIFile($activity));
    }

    /**
     * Canvas Teacher's data.
     * 
     * Save Canvas Teacher's data.
     *
     * @param Request $request
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @param UserRepositoryInterface $userRepository
     * 
     * @return Response message
     */
    public function saveLtiTeachersData(Request $request, GoogleClassroomRepositoryInterface $googleClassroomRepository, UserRepositoryInterface $userRepository)
    {
        // Save student Data for VIV if check is enabled
        if (config('student-data.save_student_data') && $request->isLearner) {
            $service = new SaveStudentdataService();
            $service->saveStudentData($request);
        }

        $lmsSetting = $this->lmsSettingRepository->findByField('lti_client_id', $request->issuerClient);
        $canvasClient = new Client($lmsSetting);
        $saveData = new SaveTeacherData($canvasClient);
        return $saveData->saveData($request, $googleClassroomRepository, $userRepository);
    }
}
