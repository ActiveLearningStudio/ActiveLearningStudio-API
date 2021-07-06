<?php

namespace App\Services\CurrikiGo;

use Illuminate\Support\Str;
use App\Services\CurrikiGo\LMSIntegrationServiceInterface;
use App\Services\CurrikiGo\TalentLMS\TalentLMS;
use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;
use App\Models\Activity;

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

	/**
	* Get XAPI file contents
	* 
	* @return string
	*/
	public function getXAPIFile(Activity $activity)
	{
		$filename = 'storage/xapifiles/' . Str::slug($activity->title, '-') . '.zip';
		$url = config('app.front_end_url') . '/activity/'.$activity->id . '/shared';
		$html = view('api.xapihtml', ['url' => $url, 'title' => $activity->title])->render();
		$xml = view('api.xapixml', ['id' => $url, 'title' => $activity->title, 'description' => $activity->content])->render();
		$zipper = new \Madnest\Madzipper\Madzipper;
		$zipper->zip($filename)
			->addString('index.html', $html)
			->addString('tincan.xml', $xml)
			->close();
		return 'public/xapifiles/' . Str::slug($activity->title, '-') . '.zip';
    }
}
