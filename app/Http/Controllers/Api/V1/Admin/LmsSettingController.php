<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLmsSetting;
use App\Http\Resources\V1\Admin\LmsSettingCollection;
use App\Http\Resources\V1\Admin\LmsSettingResource;
use App\Repositories\Admin\LmsSetting\LmsSettingRepository;
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
class LmsSettingController extends Controller
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
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/lms-setting/lms-settings.json
     *
     * @param Request $request
     * @return LmsSettingCollection
     */
    public function index(Request $request)
    {
        $collections = $this->lmsSettingRepository->getAll($request->all());
        return new LmsSettingCollection($collections);
    }

    /**
     * Get LMS Setting
     *
     * Get the specified lms setting data.
     *
     * @urlParam lms_setting required The Id of a lms setting Example: 1
     *
     * @responseFile responses/admin/lms-setting/lms-setting.json
     *
     * @param $id
     * @return LmsSettingResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $setting = $this->lmsSettingRepository->find($id);
        return new LmsSettingResource($setting->load('user'));
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
     * @return LmsSettingResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreLmsSetting $request)
    {
        $validated = $request->validated();
        $response = $this->lmsSettingRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user'))], 200);
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
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(StoreLmsSetting $request, $id)
    {
        $validated = $request->validated();
        $response = $this->lmsSettingRepository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user'))], 200);
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
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->lmsSettingRepository->destroy($id)], 200);
    }
}
