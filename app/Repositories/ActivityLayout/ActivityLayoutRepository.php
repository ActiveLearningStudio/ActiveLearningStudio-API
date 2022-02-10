<?php

namespace App\Repositories\ActivityLayout;

use App\Exceptions\GeneralException;
use App\Models\ActivityLayout;
use App\Repositories\ActivityLayout\ActivityLayoutRepositoryInterface;
use App\Repositories\BaseRepository;

class ActivityLayoutRepository extends BaseRepository implements ActivityLayoutRepositoryInterface
{
    /**
     * ActivityLayoutRepository constructor.
     *
     * @param ActivityLayout $model
     */
    public function __construct(ActivityLayout $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $query = $this->model;
        
        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->where('title', 'iLIKE', '%' . $data['query'] . '%');
        }

        if (isset($data['skipPagination']) && $data['skipPagination'] === 'true') {
            return $query->orderBy('order', 'ASC')->get();
        }

        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        return $query->orderBy('order', 'ASC')->paginate($perPage)->withQueryString();
    }


    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($item = $this->model->create($data)) {
                return $item;
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create activity Layout, please try again later!');
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
        throw new GeneralException('Unable to update activity Layout, please try again later!');
    }

    /**
     * Get the h5p content for activity item.
     *
     * @param int $libraryName for activity item
     * @param int $libraryMajorVersion for activity item
     * @param int $libraryMinorVerison for activity item
     * @return array
     */
    public function getActivityItem($libraryName, $libraryMajorVersion, $libraryMinorVerison) {

        // Create full name of library with major and minor version
        $libName =  $libraryName. ' ' . $libraryMajorVersion . '.' .$libraryMinorVerison ;

        return $this->model::select('id', 'h5pLib', 'activity_type_id')->where('h5pLib', $libName)->with('activityType')->first();
    }
}
