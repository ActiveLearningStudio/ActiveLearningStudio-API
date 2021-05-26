<?php

namespace App\Services\CurrikiGo\TalentLMS;

use App\Models\Project;
use App\Models\Activity;

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
	        $response = $client->request(
	        	'POST',
	        	$this->settings->lms_url.'/unit/create/type:IFrame,course_id:'.$course['id'],
	        	['form_params' => $params]
	        );
        }
		return "ok";
	}
}