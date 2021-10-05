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
     */
    public function getAll($data)
    {
        $query = $this->model;
        // if specific index projects requested
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
}
