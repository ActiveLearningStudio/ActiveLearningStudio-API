<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class CreateModuleCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $courseId;
    private $moduleData;

    public function __construct($courseId, $moduleData)
    {
        $this->courseId = $courseId;
        $this->moduleData = $this->prepareModuleData($moduleData);
    }

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

    public function prepareModuleData($data)
    {
        $module["name"] = $data['name'];
        $module["publish_final_grade"] = true;
        return ["module" => $module];
    }
}
