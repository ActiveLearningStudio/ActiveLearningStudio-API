<?php

namespace App\Repositories\ActivityItem;

use App\Exceptions\GeneralException;
use App\Models\ActivityItem;
use App\Repositories\BaseRepository;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use Illuminate\Support\Collection;

class ActivityItemRepository extends BaseRepository implements ActivityItemRepositoryInterface
{
    /**
     * ActivityItemRepository constructor.
     *
     * @param ActivityItem $model
     */
    public function __construct(ActivityItem $model)
    {
        parent::__construct($model);
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
                return $item;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
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
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity Item, please try again later!');
    }    
}
