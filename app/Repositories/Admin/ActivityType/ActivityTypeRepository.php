<?php

namespace App\Repositories\Admin\ActivityType;

use App\Exceptions\GeneralException;
use App\Models\ActivityType;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class ActivityTypeRepository
 * @package App\Repositories\Admin\ActivityType
 */
class ActivityTypeRepository extends BaseRepository
{

    /**
     * ActivityTypeRepository constructor.
     * @param ActivityType $model
     */
    public function __construct(ActivityType $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $this->setDtParams($data)->enableRelDtSearch(['title'], $this->dtSearchValue);
        $this->query = $this->model->when($data['q'] ?? null, function ($query) use ($data) {
            return $query->search(['title'], $data['q']);
        });
        return $this->getDtPaginated(['activityItems']);
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
            if ($type = $this->model->create($data)) {
                return ['message' => 'Activity type created Successfully!', 'data' => $type];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create activity type, please try again later!');
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
                return ['message' => 'Activity Type data updated!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity type, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($type = $this->model->find($id)) {
            return $type;
        }
        throw new GeneralException('Activity type not found!');
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
            return 'Activity Type Deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete activity type, please try again later!');
    }
}
