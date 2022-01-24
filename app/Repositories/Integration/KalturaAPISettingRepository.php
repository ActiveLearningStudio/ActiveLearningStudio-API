<?php
/**
 * @author        Asim Sarwar
 * Class          KalturaAPISettingRepository
 */
namespace App\Repositories\Integration;

use App\Exceptions\GeneralException;
use App\Models\Integration\KalturaAPISetting;
use App\Models\Organization;
use App\Repositories\BaseRepository;
use App\Repositories\Integration\KalturaAPISettingInterface;
use Illuminate\Support\Facades\Log;

class KalturaAPISettingRepository extends BaseRepository implements KalturaAPISettingInterface
{

    protected $model;
    /**
     * KalturaAPISettingRepository constructor
     * @param KalturaAPISetting $model
     */
    public function __construct(KalturaAPISetting $model)
    {
        $this->model = $model;
    }

    /**
     * To get row record of Kaltura API Setting
     * @param integer $suborganization, $id
     * @return mixed
     * @throws GeneralException
     */
    public function getRowRecordByOrgId($suborganization, $id)
    {
        $setting = $this->model->where('organization_id', $suborganization)->find($id);
        if ($setting) {
            return $setting;
        }
        throw new GeneralException('Kaltura API setting not found.');
    }

    /**
     * @param array $data, integer $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model->with(['user', 'organization']);
        if (isset($data['query']) && $data['query'] !== '') {
            $query->where(function ($query) use ($data) {
                $query = $query->whereHas('user', function ($qry) use ($data) {
                    $qry->where('email', 'iLIKE', '%' . $data['query'] . '%');
                });
                $query->orWhere('partner_id', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('sub_partner_id', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('name', 'iLIKE', '%' . $data['query'] . '%');
                $query->orWhere('email', 'iLIKE', '%' . $data['query'] . '%');
            });
        }
        return $query->where('organization_id', $suborganization->id)->paginate($perPage);
    }

    /**
     * @param  array $data
     * @return mixed
     * @throws GeneralException
     */
    public function create($data)
    {
        try {
            if ($createSetting = $this->model->create($data)) {
                return ['message' => 'Kaltura API setting created successfully!', 'data' => $createSetting];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to create Kaltura API setting, please try again later!');
    }

    /**
     * @param  array $data, integer $id
     * @return mixed
     * @throws GeneralException
     */
    public function update($id, $data)
    {
        try {
            if ($this->find($id)->update($data)) {
                return ['message' => 'Kaltura API setting updated successfully!', 'data' => $this->find($id)];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to update Kaltura API setting, please try again later!');
    }

    /**
     * @param  integer $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        if ($setting = $this->model->find($id)) {
            return $setting;
        }
        throw new GeneralException('Kaltura API setting not found.');
    }

    /**
     * @param  integer $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {            
            $this->find($id)->delete();
            return ['message' => 'Kaltura API setting deleted!', 'data' => []];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException($e->getMessage());
    }

    /**
     * To clone Kaltura API Setting
     * @param KalturaAPISetting $kalturaAPISetting
     * @param Organization $subOrganization
     * @param string $token
     * @return int id
     */
    public function clone(KalturaAPISetting $kalturaAPISetting, Organization $subOrganization, $token)
    {
        $kalturaAPISettingData = [
            "user_id" => get_user_id_by_token($token),
            "organization_id" => $subOrganization->id,
            "partner_id" => $kalturaAPISetting->partner_id,
            "sub_partner_id" => $kalturaAPISetting->sub_partner_id,
            "name" => $kalturaAPISetting->name,
            "email" => $kalturaAPISetting->email,
            "expiry" => $kalturaAPISetting->expiry,
            "session_type" => $kalturaAPISetting->session_type,
            "admin_secret" => $kalturaAPISetting->admin_secret,
            "user_secret" => $kalturaAPISetting->user_secret,
            "privileges" => $kalturaAPISetting->privileges,
            "description" => $kalturaAPISetting->description
        ];
        $clonedSetting = $this->create($kalturaAPISettingData);
        return $clonedSetting['id'];
    }
}
