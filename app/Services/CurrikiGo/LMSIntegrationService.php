<?php

namespace App\Services\CurrikiGo;

use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use App\Services\CurrikiGo\TalentLMS\TalentLMS;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;

/**
 * Generic LMS Integration Service
 */
class LMSIntegrationService implements LMSIntegrationServiceInterface
{
	protected $lms;
	protected $targets = [
		'talentlms' => TalentLMS::class,
	];

	public function getLogin($targetLMS)
	{
		return $this->lms->getLogin();
	}

	public function doLogin($targetLms, LmsSetting $setting, $params)
	{
		if (!array_key_exists($targetLms, $this->targets)) {
			return false;
		}

		$this->lms = new $this->targets[$targetLms]($setting);
		return $this->lms->doLogin($params);
	}

    /**
     * Publishes a project to the target LMS
     *
     * @param $project
     *
     * @return void
     */
	public function publishProject($targetLms, Project $project, LmsSetting $setting)
	{
		if (!array_key_exists($targetLms, $this->targets)) {
			return false;
		}

		$this->lms = new $this->targets[$targetLms]($setting);
		return $this->lms->publishProject($project);
	}
}