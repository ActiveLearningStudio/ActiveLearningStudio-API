<?php

namespace App\Repositories\LtiTool;

use App\Exceptions\GeneralException;
use App\Models\LtiTool\LtiToolSetting;
use App\Repositories\Admin\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021
 * Class          LtiToolSettingRepository
 */
class LtiToolSettingRepository extends BaseRepository
{

    /**
     * LtiToolSettingRepository constructor
     * @param LtiToolSetting $model
     */
    public function __construct(LtiToolSetting $model)
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
            if ($createSetting = $this->model->create($data)) {
                return ['message' => 'Lti Tool setting created successfully!', 'data' => $createSetting];
            }            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create lti tool setting, please try again later!');
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
                return ['message' => 'Lti tool setting updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update lti tool setting, please try again later!');
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
        throw new GeneralException('Lti tool setting not found.');
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
            return ['message' => 'Lti tool setting deleted!', 'data' => []];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete lti tool setting, please try again later!');
    }
}
