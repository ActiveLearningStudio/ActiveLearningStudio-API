<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class GetModuleItemsCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $courseId;
    private $moduleId;

    public function __construct($courseId, $moduleId = null)
    {
        $this->courseId = $courseId;
        $this->moduleId = $moduleId;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->apiURL . '/courses/' . $this->courseId . '/modules/' . $this->moduleId . '/items';
            $response = $this->httpClient->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {

        }
        return $response;
    }
}
