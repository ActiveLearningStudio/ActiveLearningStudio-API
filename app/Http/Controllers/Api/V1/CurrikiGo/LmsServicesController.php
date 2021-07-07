<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
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
     * LmsServicesController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository, LMSIntegrationServiceInterface $lms)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->lms = $lms;
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
}
