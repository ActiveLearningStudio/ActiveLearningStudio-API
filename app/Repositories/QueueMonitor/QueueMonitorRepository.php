<?php

namespace App\Repositories\QueueMonitor;

use App\Models\QueueMonitor;
use App\Repositories\Admin\BaseRepository;

/**
 * Class UserRepository.
 */
class QueueMonitorRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     *
     * @param QueueMonitor $model
     */
    public function __construct(QueueMonitor $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $filter = $data['filter'] ?? 'all';
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        // filter mapping => 1 is for running jobs, 2 for failed, 3 for completed jobs
        $this->query = $this->model->when($filter !== 'all', function ($query) use ($filter) {
            if ((int)$filter === 1) {
                return $query->running();
            }
            if ((int)$filter === 2) {
                return $query->failed();
            }
            return $query->succeeded();
        });

        if (isset($data['query']) && $data['query'] !== '') {
            $this->query = $this->model->where('name', 'iLIKE', '%' . $data['query'] . '%');
        }
        return $this->query->paginate($perPage)->appends(request()->query());
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getJobs($data)
    {
        $filter = $data['filter'] ?? 1; // default for pending jobs
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        // filter mapping => 1 is for pending jobs, 2 for failed
        if ((int)$filter === 1) {
            $this->query = \DB::table('jobs');
        }
        if ((int)$filter === 2) {
            $this->query = \DB::table('failed_jobs');
        }

        // if search parameter is set
        if (isset($data['query'])) {
            $this->query = $this->query->where(function ($query) use($data) {
                $columns = ['id', 'queue', 'payload'];
                foreach ($columns as $column) {
                    $query->orWhere($column, 'ILIKE', '%' . $data['query'] . '%');
                }
                return $query;
            });
        }
        
        return $this->query->paginate($perPage)->appends(request()->query());
    }
}
