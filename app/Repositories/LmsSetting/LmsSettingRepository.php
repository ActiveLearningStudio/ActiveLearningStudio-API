<?php

namespace App\Repositories\LmsSetting;

use App\Exceptions\GeneralException;
use App\Models\CurrikiGo\LmsSetting;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * Class UserRepository.
 */
class LmsSettingRepository extends BaseRepository
{

    /**
     * UserRepository constructor.
     *
     * @param LmsSetting $model
     */
    public function __construct(LmsSetting $model)
    {
        $this->model = $model;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;

        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->whereHas('user', function ($qry) use ($data) {
                $qry->where('first_name', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('last_name', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('email', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orwhere('lms_url', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('site_name', 'iLIKE', '%' . $data['query'] . '%');
            });
        }

         return $query->with(['user', 'organization'])->where('organization_id', $suborganization->id)->paginate($perPage);
    }

    /**
     * @param $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($setting = $this->model->create($data)) {
                return ['message' => 'Setting created successfully!', 'data' => $setting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create setting, please try again later!');
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
                return ['message' => 'Setting updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update setting, please try again later!');
    }

    /**
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Setting not found.');
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
            return 'Setting deleted!';
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete setting, please try again later!');
    }
}
