<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreActivityItem;
use App\Http\Resources\V1\ActivityItemResource;
use App\Repositories\Admin\ActivityItem\ActivityItemRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ActivityItemController extends Controller
{
    private $repository;

    /**
     * ActivityItemController constructor.
     *
     * @param ActivityItemRepository $repository
     */
    public function __construct(ActivityItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->repository->getAll($request->all());
        return ActivityItemResource::collection($collections);
    }

    /**
     * @param $id
     * @return ActivityItemResource
     * @throws GeneralException
     */
    public function edit($id)
    {
        $item = $this->repository->find($id);
        return new ActivityItemResource($item);
    }

    /**
     * @param StoreActivityItem $request
     * @return ActivityItemResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(StoreActivityItem $request)
    {
        $validated = $request->validated();
        $response = $this->repository->create($validated);
        return response(['message' => $response['message'], 'data' => new ActivityItemResource($response['data'])], 200);
    }

    /**
     * @param StoreActivityItem $request
     * @param $id
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(StoreActivityItem $request, $id)
    {
        $validated = $request->validated();
        $response = $this->repository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new ActivityItemResource($response['data'])], 200);
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
