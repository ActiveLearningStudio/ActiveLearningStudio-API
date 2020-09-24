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
        $this->setDtParams($data);
        $this->query = $this->model->whereHas('activityType', function ($query) {
            return $query->where('title', 'ILIKE', '%' . $this->dtSearchValue . '%');
        });
        return $this->getDtPaginated(['activityType']);
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
            $data['image'] = \Storage::url($data['image']->store('/public/uploads'));
            $item = $this->model->create($data);
            return 'Activity Item created successfully!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException('Unable to create activity Item, please try again later!');
        }
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
            $item = $this->find($id)->update($data);
            return 'Activity Item data updated!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException('Unable to update activity Item, please try again later!');
        }
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
            $this->model->find($id)->delete();
            return 'Activity Item Deleted!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException('Unable to delete activity Item, please try again later!');
        }
    }
}
