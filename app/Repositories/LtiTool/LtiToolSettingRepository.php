<?php

namespace App\Repositories\LtiTool;

use App\Exceptions\GeneralException;
use App\Models\LtiTool\LtiToolSetting;
use App\Models\Organization;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Log;

/**
 * @author        Asim Sarwar
 * Date           11-10-2021
 * Class          LtiToolSettingRepository
 */
class LtiToolSettingRepository extends BaseRepository implements LtiToolSettingInterface
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
        $query = $this->model->with(['user', 'organization', 'mediaSources']);
        if (isset($data['query']) && $data['query'] !== '') {
            $query->where(function ($query) use ($data) {
                $query = $query->whereHas('user', function ($qry) use ($data) {
                    $qry->where('email', 'iLIKE', '%' . $data['query'] . '%');
                });
                $query->orWhere('tool_name', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('tool_url', 'iLIKE', '%' . $data['query'] . '%');
            });
        }
        if (isset($data['order_by_column']) && $data['order_by_column'] !== '')
        {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query->orderBy($data['order_by_column'], $orderByType);
        } else {
            $query->orderBy('id', 'DESC');
        }

        if (isset($data['filter']) && $data['filter'] > 0) {
            $query = $query->whereHas('mediaSources', function ($qry) use ($data) {
                $qry->where('id', $data['filter']);
            });
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

    /**
     * To get row record by org and tool type match
     *
     * @param $orgId integer
     * @param $mediaSourcesId int
     * @return object
     * @throws GeneralException
     */
    public function getRowRecordByOrgAndToolType($orgId, $mediaSourcesId)
    {
        try {            
            return $this->model->where([['organization_id','=', $orgId],['media_source_id','=', $mediaSourcesId]])->first();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
