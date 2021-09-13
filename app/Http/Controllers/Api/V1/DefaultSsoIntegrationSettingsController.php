<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\DefaultSsoIntegrationSettingsRequest;
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
    public function index(DefaultSsoIntegrationSettingsRequest $defaultSsoSettingsRequest)
    {

        $response = $this->defaultSsoSettingsRepository->getAll($defaultSsoSettingsRequest);
        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DefaultSsoIntegrationSettingsRequest $defaultSsoSettingsRequest)
    {
//        dd($defaultSsoSettingsRequest->all());
        $validated = $defaultSsoSettingsRequest->validated();
        $response = $this->defaultSsoSettingsRepository->create($validated);
        dd($response);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $setting = $this->defaultSsoSettingsRepository->find($id);
        dd($setting);
        return new LmsSettingResource($setting->load('user', 'organization'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DefaultSsoIntegrationSettingsRequest $defaultSsoIntegrationSettingsRequest, $id)
    {
        //
        $validated = $defaultSsoIntegrationSettingsRequest->validated();
        $response = $this->defaultSsoSettingsRepository->update($id, $validated);
        dd($response);
        return response(['message' => $response['message'], 'data' => new LmsSettingResource($response['data']->load('user', 'organization'))], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        return response(['message' => $this->defaultSsoSettingsRepository->destroy($id)], 200);
    }
}
