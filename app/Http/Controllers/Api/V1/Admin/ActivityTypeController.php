<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityType;
use App\Http\Resources\V1\ActivityTypeResource;
use App\Http\Resources\V1\Admin\LmsSettingResource;
use App\Repositories\Admin\ActivityType\ActivityTypeRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ActivityTypeController extends Controller
{
    private $repository;

    /**
     * ActivityTypeController constructor.
     * @param ActivityTypeRepository $repository
     */
    public function __construct(ActivityTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->repository->getAll($request->all());
        return ActivityTypeResource::collection($collections);
    }

    /**
     * @param $id
     * @return ActivityTypeResource
     * @throws GeneralException
     */
    public function edit($id)
    {
        $type = $this->repository->find($id);
        return new ActivityTypeResource($type);
    }

    /**
     * @param StoreActivityType $request
     * @return LmsSettingResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreActivityType $request)
    {
        $validated = $request->validated();
        $response = $this->repository->create($validated);
        return response(['message' => $response['message'], 'data' => new ActivityTypeResource($response['data'])], 200);
    }

    /**
     * @param StoreActivityType $request
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(StoreActivityType $request, $id)
    {
        $validated = $request->validated();
        $response = $this->repository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new ActivityTypeResource($response['data'])], 200);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->repository->destroy($id)], 200);
    }
}
