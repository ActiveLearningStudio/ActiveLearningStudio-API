<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Brightcove API Setting
 * ClassName    BrightcoveAPISettingsController
 */

namespace App\Http\Controllers\Api\V1\Integration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Models\Organization;
use App\Models\Integration\BrightcoveAPISetting;
use App\Http\Requests\V1\Integration\StoreBrightcoveAPISetting;
use App\Http\Requests\V1\Integration\UpdateBrightcoveAPISetting;
use App\Repositories\Integration\BrightcoveAPISettingRepository;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingCollection;
use App\Http\Resources\V1\Integration\BrightcoveAPISettingResource;
use App\Jobs\CloneBrightcoveAPISetting;

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
     * @queryParam Organization $suborganization
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     * @responseUrl domain-name/api/#get-all-brightcove-api-sSettings-for-listing
     * @param Request $request
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
     * @urlParam id required The Id of a brightcove_api_settings table Example: 1
     * @urlParam Organization $suborganization
     * @responseFile domain-name/api/#get-brightcove-api-setting     
     * @param  Organization $suborganization, integer $id
     * @return BrightcoveAPISettingResource
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->bcAPISettingRepository->find($id);
        return new BrightcoveAPISettingResource($setting->load('user', 'organization'));
    }

    /**
     * Create Brightcove API Setting
     * Creates the new brightcove api setting in database.     *
     * @response {
     *   "message": "Brightcove API Setting created successfully!",
     *   "data": ["Created Setting Data Array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }
     * @param StoreBrightcoveAPISetting $request
     * @param Organization $suborganization
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
            'description'
        ]);
        $response = $this->bcAPISettingRepository->create($data);
        return response(['message' => $response['message'], 'data' => new BrightcoveAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Update Brightcove API Setting
     * Updates the brightcove_api_settings table in database.
     * @urlParam id required The Id of a brightcove_api_settings table Example: 1
     * @response {
     *   "message": "Brightcove API setting data updated successfully!",
     *   "data": ["Updated Brightcove API setting data array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to update Brightcove API setting, please try again later."
     *   ]
     * }
     * @param UpdateBrightcoveAPISetting $request
     * @param Organization $suborganization
     * @param integer $id
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
            'description'
        ]);
        $response = $this->bcAPISettingRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new BrightcoveAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Delete Brightcove API Setting
     * Deletes the brightcove_api_settings table from database.
     * @urlParam id required The Id of a brightcove_api_settings Example: 1
     * @response {
     *   "message": "Brightcove API setting deleted successfully!",
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to delete Brightcove API setting, please try again later."
     *   ]
     * }
     * @param Organization $suborganization
     * @param integer $id
     * @return Response
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->bcAPISettingRepository->destroy($id)], 200);
    }

    /**
     * Clone Brightcove API Settings
     * Clone the specified brightcove api settings of a user.
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam BrightcoveAPISetting required The Id of a BrightcoveAPISetting Example: 1
     * @bodyParam user_id optional The Id of a user Example: 1
     * @response {
     *   "message": "Brightcove API setting is being cloned in background!"
     * }
     * @response 400 {
     *   "errors": [
     *     "Unable to clone."
     *   ]
     * }
     * @param Request $request
     * @param Organization $suborganization
     * @param BrightcoveAPISetting $brightcoveAPISetting
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
    
}
