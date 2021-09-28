<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreDefaultSsoSettingsRequest;
use App\Http\Requests\V1\UpdateDefaultSsoSettingsRequest;
use App\Http\Resources\V1\DefaultSsoSettingsCollection;
use App\Http\Resources\V1\DefaultSsoSettingsResource;
use App\Models\DefaultSsoIntegrationSettings;
use App\Repositories\DefaultSsoIntegrationSettings\DefaultSsoIntegrationSettingsInterface;
use Illuminate\Http\Request;

class DefaultSsoIntegrationSettingsController extends Controller
{

    private $defaultSsoSettingsRepository;

    /**
     * DefaultSsoIntegrationSettingsController constructor.
     *
     * @param DefaultSsoIntegrationSettingsInterface $defaultSsoIntegrationSettingsRepository
     */
    public function __construct(DefaultSsoIntegrationSettingsInterface $defaultSsoIntegrationSettingsRepository)
    {
        $this->defaultSsoSettingsRepository = $defaultSsoIntegrationSettingsRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        $this->authorize('viewAny', $defaultSsoIntegrationSettings);

        $collections = $this->defaultSsoSettingsRepository->getAll($request);
        return new DefaultSsoSettingsCollection($collections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request/StoreDefaultSsoSettingsRequest $storeDefaultSsoSettingsRequest
     * @param DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDefaultSsoSettingsRequest $storeDefaultSsoSettingsRequest,
        DefaultSsoIntegrationSettings $defaultSsoIntegrationSettings)
    {
        $this->authorize('create', $defaultSsoIntegrationSettings);

        $validated = $storeDefaultSsoSettingsRequest->validated();
        $response = $this->defaultSsoSettingsRepository->create($validated);
        return response(
            [
                'message' => $response['message'],
                'data' => new DefaultSsoSettingsResource($response['data']->load('organization'))
            ],
            200
        );
    }

    /**
     * Display the specified resource.
     *
     * @param DefaultSsoIntegrationSettings $default_sso_setting
     * @return \Illuminate\Http\Response
     */
    public function show(DefaultSsoIntegrationSettings $default_sso_setting)
    {
        $this->authorize('view', [DefaultSsoIntegrationSettings::class, $default_sso_setting]);

        $setting = $this->defaultSsoSettingsRepository->find($default_sso_setting->id);
        return new DefaultSsoSettingsResource($setting->load('organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request/UpdateDefaultSsoSettingsRequest $updateDefaultSsoSettingsRequest
     * @param DefaultSsoIntegrationSettings $default_sso_setting
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDefaultSsoSettingsRequest $updateDefaultSsoSettingsRequest,
        DefaultSsoIntegrationSettings $default_sso_setting)
    {
        $this->authorize('update', [DefaultSsoIntegrationSettings::class, $default_sso_setting]);

        $validated = $updateDefaultSsoSettingsRequest->validated();
        $response = $this->defaultSsoSettingsRepository->update($default_sso_setting->id, $validated);
        return response(
            [
                'message' => $response['message'],
                'data' => new DefaultSsoSettingsResource($response['data']->load('organization'))
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DefaultSsoIntegrationSettings $default_sso_setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(DefaultSsoIntegrationSettings $default_sso_setting)
    {
        $this->authorize('delete', [DefaultSsoIntegrationSettings::class, $default_sso_setting]);
        return response(
            [
                'message' => $this->defaultSsoSettingsRepository->destroy($default_sso_setting->id)
            ],
            200
        );
    }
}
