<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreDefaultSsoSettingsRequest;
use App\Http\Requests\V1\UpdateDefaultSsoSettingsRequest;
use App\Http\Resources\V1\DefaultSsoSettingsCollection;
use App\Http\Resources\V1\DefaultSsoSettingsResource;
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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $collections = $this->defaultSsoSettingsRepository->getAll($request);
        return new DefaultSsoSettingsCollection($collections);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request/StoreDefaultSsoSettingsRequest $storeDefaultSsoSettingsRequest
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDefaultSsoSettingsRequest $storeDefaultSsoSettingsRequest)
    {
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $setting = $this->defaultSsoSettingsRepository->find($id);
        return new DefaultSsoSettingsResource($setting->load('organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request/UpdateDefaultSsoSettingsRequest $updateDefaultSsoSettingsRequest
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDefaultSsoSettingsRequest $updateDefaultSsoSettingsRequest, $id)
    {
        $validated = $updateDefaultSsoSettingsRequest->validated();
        $response = $this->defaultSsoSettingsRepository->update($id, $validated);
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
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(
            [
                'message' => $this->defaultSsoSettingsRepository->destroy($id)
            ],
            200
        );
    }
}
