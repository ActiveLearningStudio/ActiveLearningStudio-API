<?php

namespace App\Repositories\ActivityType;

use App\Exceptions\GeneralException;
use App\Models\ActivityType;
use App\Repositories\BaseRepository;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;

class ActivityTypeRepository extends BaseRepository implements ActivityTypeRepositoryInterface
{
    /**
     * ActivityTypeRepository constructor.
     *
     * @param ActivityType $model
     */
    public function __construct(ActivityType $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;

        // if specific index projects requested
        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->where('title', 'iLIKE', '%'.$data['query'].'%');
        }

        return $query->paginate($perPage);
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
                return $type;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
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
                return $this->find($id);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity type, please try again later!');
    }    
}
