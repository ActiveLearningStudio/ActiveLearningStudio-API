<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles module creation via API in Canvas
 */
class CreateModuleCommand implements Command
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
     * Course id to create a module in
     */
    private $courseId;
    /**
     * Module data
     * 
     * @var array
     */
    private $moduleData;

    /**
     * Creates an instance of the command class
     * 
     * @param string|int $courseId
     * @param array $moduleData
     * @return void
     */
    public function __construct($courseId, $moduleData)
    {
        $this->courseId = $courseId;
        $this->moduleData = $this->prepareModuleData($moduleData);
    }

    /**
     * Execute an API request for creating a module
     * 
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {            
            $response = $this->httpClient->request('POST', $this->apiURL . '/courses/' . $this->courseId . '/modules', [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json'],
                    'json' => $this->moduleData
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

    /**
     * Prepare module data for API payload
     * 
     * @param array $data
     * @return array
     */
    public function prepareModuleData($data)
    {
        $module["name"] = $data['name'];
        $module["publish_final_grade"] = true;
        return ["module" => $module];
    }
}
