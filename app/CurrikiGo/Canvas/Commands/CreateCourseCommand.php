<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles course creation via API in Canvas
 */
class CreateCourseCommand implements Command
{
    /**
     * API URL
     * 
     * @var string
     */
    public $apiURL;
    /**
     * Access Token for api requests
     * 
     * @var string
     */
    public $accessToken;
    /**
     * HTTP Client instance
     * 
     * @var \GuzzleHttp\Client
     */
    public $httpClient;
    /**
     * Account Id
     * 
     * @var int|string
     */
    private $accountId;
    /**
     * Course data
     * 
     * @var array
     */
    private $courseData;

    /**
     * Creates an instance of the command class
     * 
     * @param string|int $accountId
     * @param array $courseData
     * @param array $sisId
     * @return void
     */
    public function __construct($accountId, $courseData, $sisId)
    {
        $this->accountId = $accountId;
        $this->courseData = $this->prepareCourseData($courseData, $sisId);
    }

    /**
     * Execute an API request for creating a course
     * 
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {            
            $response = $this->httpClient->request('POST', $this->apiURL . '/accounts/' . $this->accountId . '/courses', [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json'],
                    'json' => $this->courseData
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

    /**
     * Prepare course data for API payload
     * 
     * @param array $data
     * @return array
     */
    public function prepareCourseData($data, $sisId)
    {
        $course["name"] = $data['name'];
        $short_name = strtolower(implode('-', explode(' ', $data['name'])));
        $course["course_code"] = $short_name;
        $course["sis_course_id"] = $sisId;
        $course["license"] = "public_domain";
        $course["public_syllabus_to_auth"] = true;
        $course["public_description"] = $course["name"] . " by CurrikiStudio";
        $course["default_view"] = "modules";
        $course["course_format"] = "online";
        $enrollMe = true;
        return ["course" => $course, "enroll_me" => $enrollMe];
    }
}
