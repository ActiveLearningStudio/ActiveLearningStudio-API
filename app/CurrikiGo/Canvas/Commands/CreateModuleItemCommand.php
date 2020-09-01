<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class CreateModuleItemCommand implements Command
{
    const MODULE_TYPE = 'ExternalTool';

    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $courseId;
    private $moduleId;
    private $itemData;
    
    public function __construct($courseId, $moduleId, $itemData)
    {
        $this->courseId = $courseId;
        $this->moduleId = $moduleId;
        $this->itemData = $this->prepareModuleItemData($itemData);
    }

    public function execute()
    {
        $response = null;
        try {            
            $url = $this->apiURL . '/courses/' . $this->courseId . '/modules/' . $this->moduleId . '/items';
            $response = $this->httpClient->request('POST', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json'],
                    'json' => $this->itemData
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

    public function prepareModuleItemData($data)
    {
        $moduleItem['title'] = $data['title'];
        $moduleItem['type'] = self::MODULE_TYPE;
        $moduleItem['content_id'] = $data["content_id"];
        $moduleItem['external_url'] = $data["external_url"];
        $moduleItem['new_tab'] = false;
        $moduleItem['completion_requirement']['type'] = 'must_view';
        return ["module_item" => $moduleItem];
    }
}
