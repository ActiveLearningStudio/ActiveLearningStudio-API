<?php

namespace App\Http\Controllers\Api\V1\CurrikiGo;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CurrikiGo\LmsSettingResource;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\CurrikiGo\LmsSetting\LmsSettingRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Validator;
use Redirect;

class LmsSettingController extends Controller
{
    private $lmsSettingRepository;
    
    /**
     * LmsSettingController constructor.
     *
     * @param LmsSettingRepositoryInterface $lmsSettingRepository
     */
    public function __construct(LmsSettingRepositoryInterface $lmsSettingRepository) {
        $this->lmsSettingRepository = $lmsSettingRepository;
        $this->authorizeResource(LmsSetting::class, 'lms_setting');
    }

    /**
     * Display a listing of the LMS settings for authenticated user.
     *
     * @return Response
     */
    public function my(){
        $lms_settings = $this->lmsSettingRepository->fetchAllByUserId(auth()->user()->id);
        return response( ['lms_settings' => LmsSettingResource::collection($lms_settings)], 200);
    }

}
