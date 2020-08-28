<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class CreateModuleCommand implements Command
{
    public $api_url;
    public $access_token;
    public $http_client;
    private $course_id;
    private $module_data;

    public function __construct($course_id, $module_data) {
        $this->course_id = $course_id;
        $this->module_data = $this->prepareModuleData($module_data);
    }

    public function execute()
    {
        $response = null;
        try {            
            $response = $this->http_client->request('POST', $this->api_url.'/courses/'.$this->course_id.'/modules', [
                    'headers' => ['Authorization' => "Bearer {$this->access_token}", 'Accept' => 'application/json'],
                    'json' => $this->module_data
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

    public function prepareModuleData($data)
    {
        $module["name"] = $data['name'];
        $module["publish_final_grade"] = true;
        return ["module" => $module];
    }

}