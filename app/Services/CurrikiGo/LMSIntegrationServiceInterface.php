<?php

namespace App\Services\CurrikiGo;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;
use App\Models\Activity;

/**
 * Interface for the Generic LMS Integration Service
 */
interface LMSIntegrationServiceInterface
{

    /**
     * Returns a list of the available target LMS systems
     * 
     * @return array
     */
	// public function listAvailableTargets();

    /**
     * Return login interface implementation for the selected LMS
     * 
     * @return string // HTML
     */
	public function getLogin($targetLms);

    /**
     * Execute login against target LMS
     * 
     * @return array
     */
	public function doLogin($targetLms, LmsSetting $setting, $params);

    /**
     * Publish the project to the target LMS
     * 
     * @return bool
     */
    public function publishProject($targetLms, Project $project, LmsSetting $setting);

    /**
     * Get XAPI file contents
     * 
     * @return string
     */
    public function getXAPIFile(Activity $activity);
}
