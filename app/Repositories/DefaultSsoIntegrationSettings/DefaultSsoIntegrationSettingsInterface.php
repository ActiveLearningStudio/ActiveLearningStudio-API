<?php

namespace App\Repositories\DefaultSsoIntegrationSettings;

use App\Repositories\EloquentRepositoryInterface;

interface DefaultSsoIntegrationSettingsInterface extends EloquentRepositoryInterface
{
     /**
     * Get All Default Sso integration settings
     * @param $data
     * @return mixed
     */
    public function getAll($data);

}
