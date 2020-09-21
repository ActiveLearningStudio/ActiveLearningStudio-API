<?php

namespace App\Repositories\Admin\ActivityItem;

use App\Exceptions\GeneralException;
use App\Models\ActivityItem;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class ActivityItemRepository
 * @package App\Repositories\Admin\ActivityItem
 */
class ActivityItemRepository extends BaseRepository
{

    /**
     * ActivityItemRepository constructor.
     * @param ActivityItem $model
     */
    public function __construct(ActivityItem $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        return $this->setDtParams($data)->getDtPaginated(['activityType']);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            $item = $this->model->create($data);
            return 'Activity Item created successfully!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        $item = $this->find($id)->update($data);
        return 'Activity Item data updated!';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if ($item = $this->model->find($id)) {
            return $item;
        }
        throw new GeneralException('Activity Item Not found.');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {
            $this->model->find($id)->delete();
            return 'Activity Item Deleted!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }
}
