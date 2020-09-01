<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CurrikiGo\LmsSettingCollection;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Redirect;

/**
 * @group  LMS Settings
 * @authenticated
 *
 * APIs for LMS settings used for publishing
 */
class LmsSettingController extends Controller
{
    private $lmsSettingRepository;
    
    /**
     * LmsSettingController constructor.
     *
     * @bodyParam $lmsSettingRepository LmsSettingRepositoryInterface required repository functions
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
     * @apiResourceCollection   App\Http\Resources\V1\CurrikiGo\LmsSettingCollection
     * @apiResourceModel    App\Models\CurrikiGo\LmsSetting
     */
    public function my()
    {
        $lms_settings = $this->lmsSettingRepository->fetchAllByUserId(auth()->user()->id);
        return response(new LmsSettingCollection($lms_settings), 200);
    }
}
