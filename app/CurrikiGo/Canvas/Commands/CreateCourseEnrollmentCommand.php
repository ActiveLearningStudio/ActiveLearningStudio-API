<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles module creation via API in Canvas
 */
class CreateCourseEnrollmentCommand implements Command
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
    private $data;

    /**
     * Creates an instance of the command class
     * 
     * @param string|int $courseId
     * @param array $data
     * @return void
     */
    public function __construct($courseId, $data)
    {
        $this->courseId = $courseId;
        $this->data = $data;
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
            $response = $this->httpClient->request('POST', $this->apiURL . '/courses/' . $this->courseId . '/enrollments', [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json'],
                    'json' => $this->data
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
