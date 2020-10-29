<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Admin\QueueMonitorJobResource;
use App\Http\Resources\V1\Admin\QueueMonitorResource;
use App\Repositories\Admin\QueueMonitor\QueueMonitorRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group 1007. Admin/Queues
 *
 * APIs for queues monitoring on admin panel.
 */
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
     * Get All Queues Logs
     *
     * Returns the paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam filter 1 for running jobs, 2 for failed, 3 for completed. Default all. Example: 1
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/queue/queues.json
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        $collections = $this->queueMonitorRepository->getAll($request->all());
        return QueueMonitorResource::collection($collections);
    }

    /**
     * Get All Jobs
     *
     * Returns the pending or failed jobs paginated response with pagination links (DataTables are fully supported - All Params).
     *
     * @queryParam filter 1 for pending jobs, 2 for failed. Default 1. Example: 1
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/queue/jobs.json
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function jobs(Request $request)
    {
        $collections = $this->queueMonitorRepository->getJobs($request->all());
        return QueueMonitorJobResource::collection($collections);
    }

    /**
     * Retry Specific Failed Job
     *
     * Retry failed job by ID.
     *
     * @urlParam job required The integer Id of a job. Example: 1
     *
     * @response 200 {
     *   "message": "The failed job [1] has been pushed back onto the queue!"
     * }
     *
     * @param $job
     * @return Application|ResponseFactory|Response
     */
    public function retryJob(int $job){
        \Artisan::call("queue:retry $job");
        return response(['message' => "The failed job [$job] has been pushed back onto the queue!"], 200);
    }

    /**
     * Retry All Failed Jobs
     *
     * @response 200 {
     *   "message": "All failed jobs has been pushed back onto the queue!"
     * }
     *
     * @return Application|ResponseFactory|Response
     */
    public function retryAll(){
        \Artisan::call("queue:retry all");
        return response(['message' => "All failed jobs has been pushed back onto the queue!"], 200);
    }

    /**
     * Delete Specific Failed Job
     *
     * Delete failed job by ID.
     *
     * @urlParam job required The integer Id of a job. Example: 1
     *
     * @response 200 {
     *   "message": "Failed job deleted successfully!"
     * }
     *
     * @param $job
     * @return Application|ResponseFactory|Response
     */
    public function forgetJob(int $job){
        \Artisan::call("queue:forget $job");
        return response(['message' => "Failed job deleted successfully!"], 200);
    }

    /**
     * Delete All Failed Jobs
     *
     * @response 200 {
     *   "message": "All failed jobs deleted successfully!"
     * }
     *
     * @return Application|ResponseFactory|Response
     */
    public function forgetAll(){
        \Artisan::call("queue:flush");
        return response(['message' => "All failed jobs deleted successfully!"], 200);
    }
}
