<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class CreateModuleItemCommand implements Command
{
    public $api_url;
    public $access_token;
    public $http_client;
    private $course_id;
    private $module_id;
    private $item_data;

    public function __construct($course_id, $module_id, $item_data) {
        $this->course_id = $course_id;
        $this->module_id = $module_id;
        $this->item_data = $this->prepareModuleItemData($item_data);
    }

    public function execute()
    {
        $response = null;
        try {            
            $url = $this->api_url.'/courses/'.$this->course_id.'/modules/'.$this->module_id.'/items';
            $response = $this->http_client->request('POST', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->access_token}", 'Accept' => 'application/json'],
                    'json' => $this->item_data
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

    public function prepareModuleItemData($data)
    {

        $module_item['title'] = $data['title'];
        $module_item['type'] = "ExternalTool";
        $module_item['content_id'] = $data["content_id"];
        $module_item['external_url'] = $data["external_url"];
        $module_item['new_tab'] = false;
        $module_item['completion_requirement']['type'] = 'must_view';
        return ["module_item" => $module_item];
    }

}