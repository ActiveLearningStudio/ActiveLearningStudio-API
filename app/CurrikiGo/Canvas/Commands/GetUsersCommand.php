<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching users from Canvas LMS
 */
class GetUsersCommand implements Command
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
     * Request queryString
     * 
     * @var array
     */
    private $queryString;
    private $accountId;

    /**
     * Creates an instance of the command class
     * 
     * @param string|int $courseId
     * @param array $queryString
     * @return void
     */
    public function __construct($accountId, $queryString = '')
    {
        $this->accountId = $accountId;
        $this->queryString = $queryString;
    }

    /**
     * Executes an API request for fetching user data
     * 
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {
            $response = $this->httpClient->request('GET', $this->apiURL . '/accounts/' . $this->accountId . '/users' . $this->queryString, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}"]
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
