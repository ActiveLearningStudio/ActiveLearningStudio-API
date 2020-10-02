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
        return $this->setDtParams($data)->enableRelDtSearch(['title'], $this->dtSearchValue)->getDtPaginated(['activityType']);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            // choosing this store path because old data is being read from this path
            if (isset($data['image'])) {
                $data['image'] = \Storage::url($data['image']->store('/public/uploads'));
            }
            if ($item = $this->model->create($data)) {
                return ['message' => 'Activity Item created successfully!', 'data' => $item];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create activity Item, please try again later!');
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        try {
            // choosing this store path because old data is being read from this path
            if (isset($data['image'])) {
                $data['image'] = \Storage::url($data['image']->store('/public/uploads'));
            }
            if ($this->find($id)->update($data)) {
                return ['message' => 'Activity Item data updated!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity Item, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
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
            $this->find($id)->delete();
            return 'Activity item deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete activity item, please try again later!');
    }
}
