<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\QueueMonitorResource;
use App\Repositories\Admin\QueueMonitor\QueueMonitorRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class QueueMonitorController extends Controller
{
    private $queueMonitorRepository;

    /**
     * UserController constructor.
     * @param QueueMonitorRepository $queueMonitorRepository
     */
    public function __construct(QueueMonitorRepository $queueMonitorRepository)
    {
        $this->queueMonitorRepository = $queueMonitorRepository;
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->queueMonitorRepository->getAll($request->all());
        return QueueMonitorResource::collection($collections);
    }
}
