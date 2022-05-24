<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove API Setting
 * ClassName    BrightcoveAPISettingsController
 */

namespace App\Http\Controllers\Api\V1\Integration;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Jobs\CloneBrightcoveAPISetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Integration\BrightcoveAPISetting;
use App\Http\Requests\V1\Integration\StoreBrightcoveAPISetting;
use App\Http\Requests\V1\Integration\UpdateBrightcoveAPISetting;
use App\Repositories\Integration\BrightcoveAPISettingRepository;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingResource;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingCollection;

class BrightcoveAPISettingsController extends Controller
{
    private $bcAPISettingRepository;
    /**
     * BrightcoveAPIClientController constructor.
     * @param BrightcoveAPISettingRepository $brightcoveAPISettingRepository
     */
    public function __construct(BrightcoveAPISettingRepository $brightcoveAPISettingRepository)
    {
        $this->bcAPISettingRepository = $brightcoveAPISettingRepository;
    }

    /**
     * Get All Brightcove API Settings for listing.
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     * @param Organization $suborganization     
     * @param Request $request
     * @bodyParam start Offset for getting the paginated response, Default 0. Example: 0
     * @bodyParam length Limit for getting the paginated records, Default 10. Example: 10
     * @responseUrl domain-name/api/#get-all-brightcove-api-sSettings-for-listing
     * @return BrightcoveAPISettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $collections = $this->bcAPISettingRepository->getAll($request->all(), $suborganization);
        return new BrightcoveAPISettingCollection($collections);
    }

    /**
     * Get Brightcove API Setting
     * Get the specified Brightcove API setting data.
     * @param id required The Id of a brightcove_api_settings table Example: 1
     * @param Organization $suborganization
     * @responseFile domain-name/api/#get-brightcove-api-setting
     * @return BrightcoveAPISettingResource
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->bcAPISettingRepository->find($id);
        return new BrightcoveAPISettingResource($setting->load('user', 'organization'));
    }

    /**
     * Create Brightcove API Setting Data
     * @param StoreBrightcoveAPISetting $request
     * @param Organization $suborganization
     * @response {
     *   "message": "Brightcove API Setting created successfully!",
     *   "data": ["Created Setting Data Array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }     
     * @return BrightcoveAPISettingResource
     */
    public function store(StoreBrightcoveAPISetting $request, Organization $suborganization)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'account_id',
            'account_name',
            'account_email',
            'client_id',
            'client_secret',
            'description',
            'css_path'
        ]);
        $response = $this->bcAPISettingRepository->create($data);
        return response(['message' => $response['message'], 'data' => new BrightcoveAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Update Brightcove API Setting Data
     * @param id required The Id of a brightcove_api_settings table Example: 1
     * @param UpdateBrightcoveAPISetting $request
     * @param Organization $suborganization
     * @response {
     *   "message": "Brightcove API setting data updated successfully!",
     *   "data": ["Updated Brightcove API setting data array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to update Brightcove API setting, please try again later."
     *   ]
     * }     
     * @return BrightcoveAPISettingResource
     */
    public function update(UpdateBrightcoveAPISetting $request, Organization $suborganization, $id)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'account_id',
            'account_name',
            'account_email',
            'client_id',
            'client_secret',
            'description',
            'css_path'
        ]);
        $response = $this->bcAPISettingRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new BrightcoveAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Delete Brightcove API Setting
     * @param id required The Id of a brightcove_api_settings Example: 1
     * @param Organization $suborganization
     * @response {
     *   "message": "Brightcove API setting deleted successfully!",
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to delete Brightcove API setting, please try again later."
     *   ]
     * }
     * @return Response
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->bcAPISettingRepository->destroy($id, $suborganization->id)], 200);
    }

    /**
     * Clone Brightcove API Settings
     * Clone the specified brightcove api settings of a user.
     * @param Request $request
     * @param Organization $suborganization
     * @param BrightcoveAPISetting required The Id of a BrightcoveAPISetting Example: 1
     * @bodyParam user_id optional The Id of a user Example: 1
     * @response {
     *   "message": "Brightcove API setting is being cloned in background!"
     * }
     * @response 400 {
     *   "errors": [
     *     "Unable to clone."
     *   ]
     * }
     * @return Response
     */
    public function clone(Request $request, Organization $suborganization, BrightcoveAPISetting $brightcoveAPISetting)
    {
        CloneBrightcoveAPISetting::dispatch($brightcoveAPISetting, $suborganization, $request->bearerToken())->delay(now()->addSecond());
        return response([
            "message" => "Your request to clone Brightcove API Settings [$brightcoveAPISetting->account_name] has been received and is being processed. <br> 
            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
    }

    /**
     * Upload CSS
     *
     * Upload css file for a activity type
     *
     * @bodyParam css file required typeName
     * @bodyParam css file required file
     *
     * @response {
     *   "file": "/storage/brightcove/css/Audio.file-name.css"
     * }
     *
     *
     * @param Request $request
     * @return Response
     */
    public function uploadCss(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'fileName' => 'required|string',
            'typeName' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid File.']
            ], 400);
        }

        $path = $request->file('file')->storeAs('/public/brightcove/css/', $request->fileName);

        return response([
            'file' => Storage::url($path),
        ], 200);
    }
    
}
