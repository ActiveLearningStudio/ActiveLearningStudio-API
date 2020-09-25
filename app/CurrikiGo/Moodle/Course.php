<?php

namespace App\CurrikiGo\Moodle;

use App\Models\CurrikiGo\LmsSetting;
use App\Models\Project;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class for fetching courses from Moodle LMS
 */
class Course
{
    private $lmsSetting;
    private $client;

    public function __construct($lmsSetting)
    {
        $this->lmsSetting = $lmsSetting;
        $this->client = new \GuzzleHttp\Client();
    }

    /**
     * Fetch a course from Moodle LMS
     *
     * @param Project $project
     * @return \Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function fetch(Project $project)
    {
        $web_service_token = $this->lmsSetting->lms_access_token;
        $lms_host = $this->lmsSetting->lms_url;
        $web_service_function = 'local_curriki_moodle_plugin_fetch_course';

        $web_service_url = $lms_host . '/webservice/rest/server.php';
        $rquest_params = [
            'wstoken' => $web_service_token,
            'wsfunction' => $web_service_function,
            'moodlewsrestformat' => 'json',
            'name' => $project->name
        ];
        $response = $this->client->request('GET', $web_service_url, ['query' => $rquest_params]);

        return $response;
    }
}
