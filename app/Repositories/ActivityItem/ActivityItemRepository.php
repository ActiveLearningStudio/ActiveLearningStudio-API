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
     * @param $suborganization
     * @param $data
     *
     * @return mixed
     */
    public function getAll($suborganization, $data)
    {
        $query = $this->model;
        // if specific index projects requested
        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->where('title', 'iLIKE', '%' . $data['query'] . '%');
        }

        if (isset($data['skipPagination']) && $data['skipPagination'] === 'true') {
            return $query->where('organization_id', $suborganization->id)
                         ->orderBy('order', 'ASC')
                         ->orderBy('title', 'ASC')
                         ->get();
        }
        if (isset($data['filter']) && $data['filter'] !== '') {
            $query = $query->whereHas('activityType', function ($qry) use ($data) {
                $qry->where('id',$data['filter']);
            });
        }
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');

        if (isset($data['order_by_column']) && $data['order_by_column'] === 'order') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query = $query->orderBy('order', 'ASC');
        }

        return $query->where('organization_id', $suborganization->id)
                     ->orderBy('order', 'ASC')
                     ->orderBy('title', 'ASC')
                     ->paginate($perPage)->withQueryString();
    }

    /**
     * @return mixed
     */
    public function getActivityLayouts()
    {
        return $this->model->whereIn('title', [
                                        'Interactive Video',
                                        'Column Layout',
                                        'Interactive Book',
                                        'Course Presentation',
                                        'Quiz'
                                        ]
                                    )
                            ->orderBy('order', 'ASC')
                            ->get();
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
            if ($this->find($id)->update($data)) {
                return $this->find($id);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update activity Item, please try again later!');
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
