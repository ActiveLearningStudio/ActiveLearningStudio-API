<?php

namespace App\Services\CurrikiGo\TalentLMS;

use App\Models\Project;
use App\Models\Activity;
use GuzzleHttp\TransferStats;

/**
 * LMS Integration for TalentLMS
 */
class TalentLMS
{
	protected $settings;

	function __construct($settings)
    {
    	$this->settings = $settings;
    	list($protocol, $domain) = explode('://', $settings->lms_url);
        \TalentLMS::setApiKey($settings->lms_access_token);
        \TalentLMS::setProtocol($protocol);
        \TalentLMS::setDomain($domain);
    }

	public function getLogin()
	{
		return "getlogin";
	}

	public function doLogin($params)
	{
		try {
			$loginResult = \TalentLMS_User::login(['login' => $params['username'], 'password' => $params['password']]);
			$user = \TalentLMS_User::retrieve($loginResult['user_id']);
			return $user;
		} catch (\Exception $e) {
			return response(['errors' => [$e->getMessage()]], 403);
		}
	}

    /**
     * Publishes a project to the target LMS
     *
     * @param $project
     *
     * @return void
     */
	public function publishProject(Project $project)
	{
		$course = \TalentLMS_Course::create([
			'name' => $project->name,
			'description' => $project->description,
			'creator_id' => $this->settings->lti_client_id,
		]);

		// There is no API for unit creation so we need to login
		// to the server in order to submit the form ourselves
		// as if we were the user
		$user = \TalentLMS_User::login([
			'login' => $this->settings->lms_login_id,
			'password' => $this->settings->lms_access_key,
		]);

        $client = new \GuzzleHttp\Client(['cookies' => true]);
        $res = $client->request('GET', $user['login_key']);

        $playlistIds = $project->playlists()->pluck('id');
        $activities = Activity::whereIn('playlist_id', $playlistIds)->get();
        foreach ($activities as $activity) {
			$res = $client->request('GET', $this->settings->lms_url.'/unit/create/type:IFrame,course_id:'.$course['id']);
			$body = $res->getBody();
        	$bodyContents = $body->getContents();
	        $dom = new \DomDocument();
	        @ $dom->loadHTML($bodyContents);
	        $xpath = new \DOMXpath($dom);
	        $activityUrl = 'https://'.$_SERVER['SERVER_NAME'].'/genericlms/talentlms/lmsurl/'.urlencode($this->settings->lms_url).'/client/'.$this->settings->lti_client_id.'/lmscourse/'.$course['id'].'/activity/'.$activity->id;
	        $params = [
	            '_track_form' => $xpath->query("//input[@name='_track_form']")[0]->getAttribute('value'),
	            '_myToken' => $xpath->query("//input[@name='_myToken']")[0]->getAttribute('value'),
	            'completion' => 'checkbox',
	            'embed_type' => 'iframe',
	            'name' => $activity->title,
	            'url' => $activityUrl,
	            'data' => '<iframe src="'.$activityUrl.'" width="100%"></iframe>',
	            '_redirect_' => '',
	            'restored_version' => '',
	            'maxtimeallowed' => '',
	            'width' => '',
	            'height' => '',
	        ];
	        $talentUnitId = null;
	        $response = $client->request(
	        	'POST',
	        	$this->settings->lms_url.'/unit/create/type:IFrame,course_id:'.$course['id'],
	        	[
	        		'form_params' => $params,
	        		'on_stats' => function (TransferStats $stats) use (&$talentUnitId) {
    			        $talentUnitId = explode('id:', $stats->getEffectiveUri()->getPath())[1];
	        		}
	        	]
	        );

	        // We need the unit ID to create submission identifiers for the xAPI statements
	        // but we don't know it until after creation so we edit the unit after to add it
	        $res = $client->request('GET', $this->settings->lms_url.'/unit/edit/id:'.$talentUnitId);
			$body = $res->getBody();
        	$bodyContents = $body->getContents();
	        $dom = new \DomDocument();
	        @ $dom->loadHTML($bodyContents);
	        $xpath = new \DOMXpath($dom);
	        $newActivityUrl = 'https://'.$_SERVER['SERVER_NAME'].'/genericlms/talentlms/lmsurl/'.urlencode($this->settings->lms_url).'/client/'.$this->settings->lti_client_id.'/lmscourse/'.$course['id'].'/lmsunit/'.$talentUnitId.'/activity/'.$activity->id;
	        $params = [
	            '_track_form' => $xpath->query("//input[@name='_track_form']")[0]->getAttribute('value'),
	            '_redirect_' => '',
	            '_myToken' => $xpath->query("//input[@name='_myToken']")[0]->getAttribute('value'),
	            'completion' => 'checkbox',
	            'embed_type' => 'iframe',
	            'name' => $activity->title,
	            'url' => $newActivityUrl,
	            'data' => '<iframe src="'.$newActivityUrl.'" width="100%"></iframe>',
	            '_redirect_' => '',
	            'restored_version' => '',
	            'maxtimeallowed' => '',
	            'width' => '',
	            'height' => '',
	            'submit_unit' => 'Saving...',
	        ];
	        $response = $client->request(
	        	'POST',
	        	$this->settings->lms_url.'/unit/edit/id:'.$talentUnitId,
	        	['form_params' => $params]
	        );
        }
		return "ok";
	}
}