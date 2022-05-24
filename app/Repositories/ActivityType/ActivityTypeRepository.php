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
     * @param $suborganization
     * @param $data
     * 
     * @return mixed
     */
    public function getAll($suborganization, $data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;

        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->where('title', 'iLIKE', '%'.$data['query'].'%');
        }

        if (isset($data['skipPagination']) && $data['skipPagination'] === 'true') {
            return $query->where('organization_id', $suborganization->id)->orderBy('order', 'ASC')->get();
        }
        
        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->where('organization_id', $suborganization->id)->paginate($perPage)->withQueryString();
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
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
            if ($this->find($id)->update($data)) {
                return $this->find($id);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity type, please try again later!');
    }    
}
