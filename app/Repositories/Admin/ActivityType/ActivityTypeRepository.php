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
        return $this->setDtParams($data)->getDtPaginated(['activityItems']);
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
            $type = $this->model->create($data);
            return 'Activity Type created successfully!';
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
        // choosing this store path because old data is being read from this path
        if (isset( $data['image'])){
            $data['image'] = \Storage::url($data['image']->store('/public/uploads'));
        }
        $type = $this->find($id)->update($data);
        return 'Activity Type data updated!';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if ($type = $this->model->find($id)) {
            return $type;
        }
        throw new GeneralException('Activity Type Not found!');
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
            return 'Activity Type Deleted!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }
}
