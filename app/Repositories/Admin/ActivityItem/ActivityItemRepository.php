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
     * @param int $start
     * @param int $length
     * @return mixed
     */
    public function getAll($start = 0, $length = 25)
    {
        // calculate page size if not present in request
        if (!request()->has('page')) {
            $page = empty($length) ? 0 : ($start / $length);
            request()->request->add(['page' => $page + 1]);
        }
        // search through each column and sort by - Needed for datatables calls
        return $this->model->with('user')->when(isset(request()->order[0]['dir']), function ($query) {
            return $query->orderBy(request()->columns[request()->order[0]['column']]['name'], request()->order[0]['dir']);
        })->when(isset(request()->search['value']) && request()->search['value'], function ($query) {
            return $query->search(request()->columns, request()->search['value']);
        })->paginate($length);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            $setting = $this->model->create($data);
            return 'Setting created successfully!';
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
        $setting = $this->find($id)->update($data);
        return 'Setting data updated!';
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Setting Not found.');
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
            return 'Setting Deleted!';
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            throw new GeneralException($e->getMessage());
        }
    }
}
