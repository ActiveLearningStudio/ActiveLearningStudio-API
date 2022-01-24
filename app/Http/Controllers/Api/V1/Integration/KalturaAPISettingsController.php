<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Kaltura API Setting
 * ClassName    KalturaAPISettingsController
 */

namespace App\Http\Controllers\Api\V1\Integration;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Jobs\CloneKalturaAPISetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Integration\KalturaAPISetting;
use App\Http\Requests\V1\Integration\StoreKalturaAPISetting;
use App\Http\Requests\V1\Integration\UpdateKalturaAPISetting;
use App\Repositories\Integration\KalturaAPISettingRepository;
use App\Http\Resources\V1\Integration\KalturaAPISettingResource;
use App\Http\Resources\V1\Integration\KalturaAPISettingCollection;

class KalturaAPISettingsController extends Controller
{
    private $kalturaAPISettingRepository;
    /**
     * KalturaAPIClientController constructor.
     * @param KalturaAPISettingRepository $kalturaAPISettingRepository
     */
    public function __construct(KalturaAPISettingRepository $kalturaAPISettingRepository)
    {
        $this->kalturaAPISettingRepository = $kalturaAPISettingRepository;
    }

    /**
     * Get All Kaltura API Settings for listing.
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     * @param Organization $suborganization     
     * @param Request $request
     * @bodyParam start Offset for getting the paginated response, Default 0. Example: 0
     * @bodyParam length Limit for getting the paginated records, Default 10. Example: 10
     * @responseUrl domain-name/api/#get-all-kaltura-api-settings-for-listing
     * @return KalturaAPISettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $collections = $this->kalturaAPISettingRepository->getAll($request->all(), $suborganization);
        return new KalturaAPISettingCollection($collections);
    }

    /**
     * Get Kaltura API Setting
     * Get the specified Kaltura API setting data.
     * @param id required The Id of a kaltura_api_settings table Example: 1
     * @param Organization $suborganization
     * @responseFile domain-name/api/#get-kaltura-api-setting
     * @return KalturaAPISettingResource
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->kalturaAPISettingRepository->find($id);
        return new KalturaAPISettingResource($setting->load('user', 'organization'));
    }

    /**
     * Create Kaltura API Setting Data
     * @param StoreKalturaAPISetting $request
     * @param Organization $suborganization
     * @response {
     *   "message": "Kaltura API Setting created successfully!",
     *   "data": ["Created Setting Data Array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }     
     * @return KalturaAPISettingResource
     */
    public function store(StoreKalturaAPISetting $request, Organization $suborganization)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'partner_id',
            'sub_partner_id',
            'name',
            'email',
            'expiry',
            'session_type',
            'admin_secret',
            'user_secret',
            'privileges',
            'description'
        ]);
        $response = $this->kalturaAPISettingRepository->create($data);
        return response(['message' => $response['message'], 'data' => new KalturaAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Update Kaltura API Setting Data
     * @param id required The Id of a kaltura_api_settings table Example: 1
     * @param UpdateKalturaAPISetting $request
     * @param Organization $suborganization
     * @response {
     *   "message": "Kaltura API setting data updated successfully!",
     *   "data": ["Updated Kaltura API setting data array"]
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to update Kaltura API setting, please try again later."
     *   ]
     * }     
     * @return KalturaAPISettingResource
     */
    public function update(UpdateKalturaAPISetting $request, Organization $suborganization, $id)
    {
        $data = $request->only([
            'user_id',
            'organization_id',
            'partner_id',
            'sub_partner_id',
            'name',
            'email',
            'expiry',
            'session_type',
            'admin_secret',
            'user_secret',
            'privileges',
            'description'
        ]);
        $response = $this->kalturaAPISettingRepository->update($id, $data);
        return response(['message' => $response['message'], 'data' => new KalturaAPISettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Delete Kaltura API Setting
     * @param id required The Id of a kaltura_api_settings Example: 1
     * @param Organization $suborganization
     * @response {
     *   "message": "Kaltura API setting deleted successfully!",
     * }
     * @response 500 {
     *   "errors": [
     *     "Unable to delete Kaltura API setting, please try again later."
     *   ]
     * }
     * @return Response
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->kalturaAPISettingRepository->destroy($id)], 200);
    }

    /**
     * Clone Kaltura API Settings
     * Clone the specified kaltura api settings of a user.
     * @param Request $request
     * @param Organization $suborganization
     * @param KalturaAPISetting required The Id of a KalturaAPISetting Example: 1
     * @bodyParam user_id optional The Id of a user Example: 1
     * @response {
     *   "message": "Kaltura API setting is being cloned in background!"
     * }
     * @response 400 {
     *   "errors": [
     *     "Unable to clone."
     *   ]
     * }
     * @return Response
     */
    public function clone(Request $request, Organization $suborganization, KalturaAPISetting $kalturaAPISetting)
    {
        CloneKalturaAPISetting::dispatch($kalturaAPISetting, $suborganization, $request->bearerToken())->delay(now()->addSecond());
        return response([
            "message" => "Your request to clone Kaltura API Settings [" . $kalturaAPISetting->name . "|" . $kalturaAPISetting->partner_id . "] has been received and is being processed. <br> 
            You will be alerted in the notification section in the title bar when complete.",
        ], 200);
    }    
}
