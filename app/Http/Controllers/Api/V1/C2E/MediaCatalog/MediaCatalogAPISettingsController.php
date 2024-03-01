<?php

namespace App\Http\Controllers\Api\V1\C2E\MediaCatalog;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\C2E\MediaCatalog\StoreMediaCatalogAPISettingRequest;
use App\Http\Requests\V1\C2E\MediaCatalog\UpdateMediaCatalogAPISettingRequest;
use App\Http\Requests\V1\C2E\MediaCatalog\SrtContent\StoreMediaCatalogSrtContentRequest;
use App\Http\Requests\V1\C2E\MediaCatalog\SrtContent\UpdateMediaCatalogSrtContentRequest;
use App\Http\Resources\V1\C2E\MediaCatalog\MediaCatalogAPISettingCollection;
use App\Http\Resources\V1\C2E\MediaCatalog\MediaCatalogAPISettingResource;
use App\Models\C2E\MediaCatalog\MediaCatalogAPISetting;
use App\Models\Organization;
use App\Repositories\C2E\MediaCatalog\MediaCatalogAPISettingInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * @authenticated
 *
 * @group 32. Media Catalog API Settings
 *
 * APIs for media catalog api settings on admin panel.
 */
class MediaCatalogAPISettingsController extends Controller
{
    private $mediaCatalogAPISettingRepository;

    /**
     * MediaCatalogAPISettingsController constructor.
     *
     * @param MediaCatalogAPISettingInterface $mediaCatalogAPISettingRepository     
     */
    public function __construct(MediaCatalogAPISettingInterface $mediaCatalogAPISettingRepository)
    {
        $this->mediaCatalogAPISettingRepository = $mediaCatalogAPISettingRepository;
    }

    /**
     * Get All Media Catalog API Settings for listing.
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/c2e/media-catalog/settings.json
     *
     * @param Request $request
     * @param Organization $suborganization
     * 
     * @return MediaCatalogAPISettingCollection
     */
    public function index(Request $request, Organization $suborganization)
    {
        $this->authorize('viewAny', [MediaCatalogAPISetting::class, $suborganization]);

        $collections = $this->mediaCatalogAPISettingRepository->getAll($request->all(), $suborganization);
        return new MediaCatalogAPISettingCollection($collections);
    }

    /**
     * Get Media Catalog API Setting
     *
     * Get the specified setting data.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam setting required The Id of a setting Example: 1
     *
     * @responseFile responses/c2e/media-catalog/setting.json
     *
     * @param Organization $suborganization
     * @param MediaCatalogAPISetting $setting
     * @return MediaCatalogAPISettingResource
     */
    public function show(Organization $suborganization, MediaCatalogAPISetting $setting)
    {
        $this->authorize('view', $setting);

        return new MediaCatalogAPISettingResource($setting->load('user', 'organization', 'mediaSources'));
    }

    /**
     * Create Media Catalog API Settings
     * 
     * Creates the new media catalog api settings in database
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Media Catalog API Settings name Example: Brightcove API Integration     
     * @bodyParam email string required Media Catalog API Settings email Example: mike@curriki.org
     * @bodyParam url string required Media Catalog API Settings url Example: https://studio.brightcove.com     
     * @bodyParam description string optional Media Catalog API Settings description Example: Brightcove API Testing     
     * @bodyParam client_key string optional Media Catalog API Settings client key Example: 4515783 or token
     * @bodyParam secret_key string optional Required with client key. Example: Token
     *
     * @responseFile 200 responses/c2e/media-catalog/setting-create.json
     * 
     * @response 500 {
     *   "errors": [
     *     "Unable to create media catalog api setting, please try again later!"
     *   ]
     * }
     *
     * @param StoreMediaCatalogAPISettingRequest $request
     * @param Organization $suborganization
     * @return MediaCatalogAPISettingResource
     */
    public function store(StoreMediaCatalogAPISettingRequest $request, Organization $suborganization)
    {
        $this->authorize('create', [MediaCatalogAPISetting::class, $suborganization]);

        $validated = $request->validated();        
        $authUser = auth()->user();
        $validated['user_id'] = $authUser->id;
        $validated['organization_id'] = $suborganization->id;
        $response = $this->mediaCatalogAPISettingRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new MediaCatalogAPISettingResource($response['data']->load('user', 'organization', 'mediaSources'))], 200);
        
    }

    /**
     * Update Media Catalog API Setting
     * 
     * Update the specific Media Catalog API Settings record
     * 
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Media Catalog API Settings name Example: Brightcove API Integration     
     * @bodyParam email string required Media Catalog API Settings email Example: mike@curriki.org
     * @bodyParam url string required Media Catalog API Settings url Example: https://studio.brightcove.com     
     * @bodyParam description string optional Media Catalog API Settings description Example: Brightcove API Testing     
     * @bodyParam client_key string optional Media Catalog API Settings client key Example: 4515783 or token
     * @bodyParam secret_key string optional Required with client key. Example: Token
     *
     * @responseFile 200 responses/c2e/media-catalog/setting-update.json
     * 
     * @response 500 {
     *   "errors": [
     *     "Unable to create media catalog api setting, please try again later!"
     *   ]
     * }
     *
     * @param UpdateMediaCatalogAPISettingRequest $request
     * @param Organization $suborganization
     * @param MediaCatalogAPISetting $setting 
     * @return MediaCatalogAPISettingResource
     */
    public function update(UpdateMediaCatalogAPISettingRequest $request, Organization $suborganization, MediaCatalogAPISetting $setting)
    {
        $this->authorize('update', $setting);

        $validated = $request->validated();
        $authUser = auth()->user();
        $validated['user_id'] = $authUser->id;
        $validated['organization_id'] = $suborganization->id;
        $response = $this->mediaCatalogAPISettingRepository->update($setting, $validated);
        return response(['message' => $response['message'], 'data' => new MediaCatalogAPISettingResource($response['data']->load('organization', 'mediaSources'))], 200);
    }

    /**
     * Delete Media Catalog API Setting
     *
     * Deletes the setting from database.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam setting required The Id of a setting Example: 1
     *
     * @response {
     *   "message": "Media catalog api setting deleted!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Unable to delete media catalog api setting, please try again later!"
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @param MediaCatalogAPISetting $setting
     * @return Application|Factory|View
     */
    public function destroy(Organization $suborganization, MediaCatalogAPISetting $setting)
    {
        $this->authorize('delete', $setting);
        
        return response(['message' => $this->mediaCatalogAPISettingRepository->destroy($setting)], 200);
    }

    /**
     * Create Media Catalog Video SRT Content
     * 
     * Creates the media catalog video srt content in database
     * 
     * @urlParam apisetting required The Id of a media_catalog_api_settings Example: 1
     * @bodyParam video_id string required Example: 6343680181112
     * @bodyParam content text required video srt content Example: contain start, end time and text
     *
     * @responseFile 200 responses/c2e/media-catalog/srt-content/srt-content-create.json
     * 
     * @response 500 {
     *   "errors": [
     *     "Unable to create media catalog srt content, please try again later!"
     *   ]
     * }
     *
     * @param StoreMediaCatalogSrtContentRequest $request
     * @param MediaCatalogAPISetting $apisetting
     * @return MediaCatalogAPISettingResource
     */
    public function storeVideoSrtContent(StoreMediaCatalogSrtContentRequest $request, MediaCatalogAPISetting $apisetting)
    {
        
        $this->authorize('create', [MediaCatalogAPISetting::class, $apisetting->organization]);
        
        $validated = $request->validated();
        $validated['media_catalog_api_setting_id'] = $apisetting->id;
        $validated['content'] = $request->file('content')->get();
        $response = $this->mediaCatalogAPISettingRepository->createMediaCatalogSrtContent($validated);
        return response(['message' => $response['message'], 'data' => new MediaCatalogAPISettingResource($response['data']->load('apiSetting'))], 200);        
    }
}
