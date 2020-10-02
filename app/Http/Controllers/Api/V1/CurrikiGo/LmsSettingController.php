<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CurrikiGo\LmsSettingCollection;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;

/**
 * @group 9. LMS Settings
 *
 * APIs for LMS settings used for publishing
 */
class LmsSettingController extends Controller
{
    private $lmsSettingRepository;

    /**
     * LmsSettingController constructor.
     *
     * @param $lmsSettingRepository LmsSettingRepositoryInterface
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;

        $this->authorizeResource(LmsSetting::class, 'lms_setting');
    }

    /**
     * Authenticated user LMS settings
     *
     * Display a listing of the LMS settings for authenticated user
     *
     * @responseFile responses/curriki-go/lms-settings.json
     */
    public function my()
    {
        $lms_settings = $this->lmsSettingRepository->fetchAllByUserId(auth()->user()->id);
        return response(new LmsSettingCollection($lms_settings), 200);
    }
}
