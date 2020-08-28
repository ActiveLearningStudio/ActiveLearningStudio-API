<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class CreateCourseCommand implements Command
{
    public $api_url;
    public $access_token;
    public $http_client;
    private $account_id;
    private $course_data;

    public function __construct($account_id, $course_data) {
        $this->account_id = $account_id;
        $this->course_data = $this->prepareCourseData($course_data);
    }

    public function execute()
    {
        $response = null;
        try {            
            $response = $this->http_client->request('POST', $this->api_url.'/accounts/'.$this->account_id.'/courses', [
                    'headers' => ['Authorization' => "Bearer {$this->access_token}", 'Accept' => 'application/json'],
                    'json' => $this->course_data
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
        $course["sis_course_id"] = $short_name.'-'.uniqid();
        $course["license"] = "public_domain";
        $course["public_syllabus_to_auth"] = true;
        $course["public_description"] = $course["name"]." by CurrikiStudio";
        $course["default_view"] = "modules";
        $course["course_format"] = "online";
        $enroll_me = true;
        return ["course" => $course, "enroll_me" => $enroll_me];
    }

}