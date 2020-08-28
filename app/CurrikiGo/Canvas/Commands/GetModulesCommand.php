<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class GetModulesCommand implements Command
{
    public $api_url;
    public $asscess_token;
    public $http_client;
    private $course_id;
    private $module_name;

    public function __construct($course_id, $module_name = null) {
        $this->course_id = $course_id;
        $this->module_name = $module_name;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->api_url.'/courses/'.$this->course_id.'/modules';
            $url .= $this->module_name ? '?search_term='.$this->module_name : '';
            $response = $this->http_client->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->asscess_token}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}