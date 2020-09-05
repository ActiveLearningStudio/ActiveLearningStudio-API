<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles module item creation via API in Canvas
 */
class CreateModuleItemCommand implements Command
{
    /**
     * Module item type
     * 
     * @var string
     */
    const MODULE_ITEM_TYPE = 'ExternalTool';

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
     * Course Id
     * 
     * @var int
     */
    private $courseId;
    /**
     * Module Id
     * 
     * @var int
     */
    private $moduleId;
    /**
     * Item data
     * 
     * @var array
     */
    private $itemData;
    
    /**
     * Creates an instance of the command class
     * 
     * @param int $courseId
     * @param int $moduleId
     * @param array $itemData
     * @return void
     */
    public function __construct($courseId, $moduleId, $itemData)
    {
        $this->courseId = $courseId;
        $this->moduleId = $moduleId;
        $this->itemData = $this->prepareModuleItemData($itemData);
    }

    /**
     * Execute an API request for creating a module item
     * 
     * @return string|null
     */
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

    /**
     * Prepare module item data for API payload
     * 
     * @param array $data
     * @return array
     */
    public function prepareModuleItemData($data)
    {
        $moduleItem['title'] = $data['title'];
        $moduleItem['type'] = self::MODULE_ITEM_TYPE;
        $moduleItem['content_id'] = $data["content_id"];
        $moduleItem['external_url'] = $data["external_url"];
        $moduleItem['new_tab'] = false;
        $moduleItem['completion_requirement']['type'] = 'must_view';
        return ["module_item" => $moduleItem];
    }
}
