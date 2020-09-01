<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class CreateCourseCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $accountId;
    private $courseData;

    public function __construct($accountId, $courseData)
    {
        $this->accountId = $accountId;
        $this->courseData = $this->prepareCourseData($courseData);
    }

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

    public function prepareCourseData($data)
    {
        $course["name"] = $data['name'];
        $short_name = strtolower(implode('-', explode(' ', $data['name'])));
        $course["course_code"] = $short_name;
        $course["sis_course_id"] = $short_name . '-' . uniqid();
        $course["license"] = "public_domain";
        $course["public_syllabus_to_auth"] = true;
        $course["public_description"] = $course["name"] . " by CurrikiStudio";
        $course["default_view"] = "modules";
        $course["course_format"] = "online";
        $enrollMe = true;
        return ["course" => $course, "enroll_me" => $enrollMe];
    }
}
