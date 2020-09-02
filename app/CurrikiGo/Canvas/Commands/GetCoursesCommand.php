<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching courses in Canvas LMS
 */
class GetCoursesCommand implements Command
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
     * Account Id
     * 
     * @var int|string
     */
    private $accountId;
    /**
     * Program name
     * 
     * @var string
     */
    private $programName;

    /**
     * Creates an instance of the command class
     * 
     * @param int $accountId
     * @param string $programName
     * @return void
     */
    public function __construct($accountId, $programName = null)
    {
        $this->accountId = $accountId;
        $this->programName = $programName;
    }

    /**
     * Execute an API request for getting courses
     * 
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {
            $url = $this->apiURL . '/accounts/' . $this->accountId . '/courses';
            $url .= $this->programName ? '?search_term=' . $this->programName : '';
            $response = $this->httpClient->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
