<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class GetModuleItemsCommand implements Command
{
    public $api_url;
    public $asscess_token;
    public $http_client;
    private $course_id;
    private $module_id;

    public function __construct($course_id, $module_id = null) {
        $this->course_id = $course_id;
        $this->module_id = $module_id;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->api_url.'/courses/'.$this->course_id.'/modules/'.$this->module_id.'/items';
            $response = $this->http_client->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->asscess_token}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}