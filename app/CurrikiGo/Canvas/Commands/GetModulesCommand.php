<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching modules in Canvas LMS
 */
class GetModulesCommand implements Command
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
     * Course Id
     * 
     * @var int
     */
    private $courseId;
    /**
     * Module Name
     * 
     * @var string|null
     */
    private $moduleName;

    /**
     * Creates an instance of the command class
     * 
     * @param int $courseId
     * @param string|null $moduleName
     * @return void
     */
    public function __construct($courseId, $moduleName = null)
    {
        $this->courseId = $courseId;
        $this->moduleName = $moduleName;
    }

    /**
     * Executes an API request for fetching module
     * 
     * @return string|null
     */
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
