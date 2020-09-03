<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching user enrollments in Canvas LMS
 */
class GetEnrollmentsCommand implements Command
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
     * User object
     * 
     * @var object
     */
    public $user;

    /**
     * Creates an instance of the command class
     * 
     * @param object $user The User object
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute an API request for fetching user enrollments
     * 
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {
            $response = $this->httpClient->request('GET', $this->apiURL . '/users/' . $this->user->id . '/enrollments', [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}"]
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
