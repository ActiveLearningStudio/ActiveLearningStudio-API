<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\CurrikiGo\Canvas\Client;
use App\CurrikiGo\Canvas\Commands\GetCourseDetailsCommand;
use App\CurrikiGo\Canvas\SaveTeacherData as SaveTeacherData;
use App\Http\Controllers\Controller;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use App\Repositories\GoogleClassroom\GoogleClassroomRepositoryInterface;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use App\Models\CurrikiGo\LmsSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use App\Models\Activity;
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
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, LMSIntegrationServiceInterface $lms, Client $canvasClient)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->lms = $lms;
        $this->canvasClient = $canvasClient;
    }

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

    public function getXAPIFile(Request $request, Activity $activity) {
        return Storage::download($this->lms->getXAPIFile($activity));
    }

    /**
     * Save Canvas Teacher's data.
     *
     * @param Request $request
     * @param GoogleClassroomRepositoryInterface $googleClassroomRepository
     * @return Response message
     */
    public function saveLtiTeachersData(Request $request, GoogleClassroomRepositoryInterface $googleClassroomRepository)
    {
        $lmsSetting = $this->lmsSettingRepository->findByField('lti_client_id', $request->issuerClient);
        $canvasClient = new Client($lmsSetting);
        $saveData = new SaveTeacherData($canvasClient);
        return $saveData->saveData($request, $googleClassroomRepository);
    }
}
