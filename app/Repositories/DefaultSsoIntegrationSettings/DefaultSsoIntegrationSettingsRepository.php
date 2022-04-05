<?php

namespace App\Repositories\DefaultSsoIntegrationSettings;

use App\Exceptions\GeneralException;
use App\Models\DefaultSsoIntegrationSettings;
use App\Repositories\BaseRepository;
use App\Repositories\DefaultSsoIntegrationSettings\DefaultSsoIntegrationSettingsInterface;
use Illuminate\Support\Facades\Log;

class DefaultSsoIntegrationSettingsRepository extends BaseRepository implements DefaultSsoIntegrationSettingsInterface
{

    /**
     * DefaultSsoIntegrationSettingsRepository constructor.
     *
     * @param DefaultSsoIntegrationSettings $model
     */
    public function __construct(DefaultSsoIntegrationSettings $model)
    {
        parent::__construct($model);
    }

    /**
     * Get All Default Sso integration settings
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;

        if (isset($data['query']) && $data['query'] !== '') {
            $query = $query->whereHas('organization', function ($qry) use ($data) {
                $qry->where('name', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orwhere('description', 'iLIKE', '%' . $data['query'] . '%');
                $qry->orWhere('domain', 'iLIKE', '%' . $data['query'] . '%');
            })
                ->orWhere('lms_url', 'iLIKE', '%' . $data['query'] . '%')
                ->orWhere('lti_client_id', 'iLIKE', '%' . $data['query'] . '%')
                ->orWhere('site_name', 'iLIKE', '%' . $data['query'] . '%');
        }

        if (isset($data['order_by_column']) && $data['order_by_column'] !== '') {
            $orderByType = isset($data['order_by_type']) ? $data['order_by_type'] : 'ASC';
            $query = $query->orderBy($data['order_by_column'], $orderByType);
        }

        if (isset($data['filter']) && $data['filter'] !== '') {
            $query = $query->where('lms_name', $data['filter']);
        }
        return $query->with('organization')->paginate($perPage)->withQueryString();
    }

    /**
     * Create new Default Sso integration setting
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
     * Find/Search Default Sso integration setting by id
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function find($id)
    {
        try {
            if ($setting = $this->model->find($id)) {
                return $setting;
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Default Sso Setting not found.');
    }

    /**
     * Update Default Sso integration setting
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
     * Remove Default Sso integration setting
     * @param $id
     * @return mixed
     * @throws GeneralException
     */
    public function destroy($id)
    {
        try {
            if ($this->find($id)->delete()) {
                return ['message' => 'Setting deleted!', 'data' => $id];
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
        throw new GeneralException('Unable to delete setting, please try again later!');
    }
}
