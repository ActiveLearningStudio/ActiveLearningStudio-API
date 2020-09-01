<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class GetModulesCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $courseId;
    private $moduleName;

    public function __construct($courseId, $moduleName = null)
    {
        $this->courseId = $courseId;
        $this->moduleName = $moduleName;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->apiURL . '/courses/' . $this->courseId . '/modules';
            $url .= $this->moduleName ? '?search_term=' . $this->moduleName : '';
            $response = $this->httpClient->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
