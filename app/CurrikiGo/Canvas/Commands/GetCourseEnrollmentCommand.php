<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles module creation via API in Canvas
 */
class GetCourseEnrollmentCommand implements Command
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
     * Request queryString
     * 
     * @var array
     */
    private $queryString;

    /**
     * Creates an instance of the command class
     * 
     * @param string|int $courseId
     * @param array $queryString
     * @return void
     */
    public function __construct($courseId, $queryString = '')
    {
        $this->courseId = $courseId;
        $this->queryString = $queryString;
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
            $response = $this->httpClient->request('GET', $this->apiURL . '/courses/' . $this->courseId . '/enrollments' .$this->queryString , [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }

}
