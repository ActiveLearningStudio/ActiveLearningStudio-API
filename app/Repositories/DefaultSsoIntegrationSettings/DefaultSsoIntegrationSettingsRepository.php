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
     * @param $data
     * @return mixed
     */
    public function getAll($data)
    {
        $perPage = isset($data['size']) ? $data['size'] : config('constants.default-pagination-per-page');
        $query = $this->model;
        return $query->paginate($perPage);
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
