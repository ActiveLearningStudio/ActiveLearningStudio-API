<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching module items in Canvas LMS
 */
class GetModuleItemsCommand implements Command
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
     * Module Id
     * 
     * @var int
     */
    private $moduleId;

    /**
     * Creates an instance of the command class
     * 
     * @param int $courseId
     * @param int|null $moduleId
     * @return void
     */
    public function __construct($courseId, $moduleId = null)
    {
        $this->courseId = $courseId;
        $this->moduleId = $moduleId;
    }

    /**
     * Executes an API request for fetching module items
     * 
     * @return string|null
     */
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
