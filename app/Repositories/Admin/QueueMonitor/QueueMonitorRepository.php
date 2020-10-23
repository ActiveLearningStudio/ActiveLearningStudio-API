<?php

namespace App\Repositories\Admin\QueueMonitor;

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
        return $this->setDtParams($data)->getDtPaginated();
    }
}
