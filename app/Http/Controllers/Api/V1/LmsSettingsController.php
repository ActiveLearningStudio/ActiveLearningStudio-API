<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreLmsSetting;
use App\Http\Requests\V1\UpdateLmsSetting;
use App\Http\Resources\V1\LmsSettingCollection;
use App\Http\Resources\V1\LmsSettingResource;
use App\Models\Organization;
use App\Repositories\LmsSetting\LmsSettingRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @group 1005. Admin/LMS Settings
 *
 * APIs for lms settings on admin panel.
 */
class LmsSettingsController extends Controller
{
    private $lmsSettingRepository;

    /**
     * UserController constructor.
     * @param LmsSettingRepository $lmsSettingRepository
     */
    public function __construct(LmsSettingRepository $lmsSettingRepository)
    {
        $this->lmsSettingRepository = $lmsSettingRepository;
    }

    /**
     * Get All LMS Settings for listing.
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam Organization $suborganization
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/lms-setting/lms-settings.json
     *
     * @param Request $request
     * @return LmsSettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $collections = $this->lmsSettingRepository->getAll($request->all(), $suborganization);
        return new LmsSettingCollection($collections);
    }

    /**
     * Get LMS Setting
     *
     * Get the specified lms setting data.
     *
     * @urlParam lms_setting required The Id of a lms setting Example: 1
     * @urlParam Organization $suborganization
     *
     * @responseFile responses/admin/lms-setting/lms-setting.json
     *
     * @return LmsSettingResource
     * @param $id
     * @throws GeneralException
     */
    public function show(Organization $suborganization, $id)
    {
        $setting = $this->lmsSettingRepository->find($id);
        return new LmsSettingResource($setting->load('user', 'organization'));
    }

    /**
     * Create LMS Setting
     *
     * Creates the new lms setting in database.
     *
     * @response {
     *   "message": "Setting created successfully!",
     *   "data": ["Created Setting Data Array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to create setting, please try again later!"
     *   ]
     * }
     *
     * @param StoreLmsSetting $request
     * @param Organization $suborganization
     * @return LmsSettingResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreLmsSetting $request, Organization $suborganization)
    {
        $validated = $request->validated();
        $response = $this->lmsSettingRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Update LMS Setting
     *
     * Updates the lms setting in database.
     *
     * @urlParam lms_setting required The Id of a lms setting Example: 1
     *
     * @response {
     *   "message": "LMS setting data updated successfully!",
     *   "data": ["Updated LMS setting data array"]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to update LMS setting, please try again later."
     *   ]
     * }
     *
     * @param StoreLmsSetting $request
     * @param Organization $suborganization
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(UpdateLmsSetting $request, Organization $suborganization, $id)
    {
        $validated = $request->validated();
        $response = $this->lmsSettingRepository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Delete LMS Setting
     *
     * Deletes the lms setting from database.
     *
     * @urlParam lms_setting required The Id of a lms setting Example: 1
     *
     * @response {
     *   "message": "LMS setting deleted successfully!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete LMS setting, please try again later."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy(Organization $suborganization, $id)
    {
        return response(['message' => $this->lmsSettingRepository->destroy($id)], 200);
    }
}
