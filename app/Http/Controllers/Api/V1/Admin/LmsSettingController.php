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
     * @param Request $request
     * @return LmsSettingCollection
     */
    public function index(Request $request)
    {
        $collections = $this->lmsSettingRepository->getAll($request->all());
        return new LmsSettingCollection($collections);
    }

    /**
     * @param $id
     * @return LmsSettingResource
     * @throws GeneralException
     */
    public function edit($id)
    {
        $setting = $this->lmsSettingRepository->find($id);
        return new LmsSettingResource($setting->load('user'));
    }

    /**
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
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->lmsSettingRepository->destroy($id)], 200);
    }
}
