<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles course enrollment via API in Canvas
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
     * Course id
     */
    private $courseId;
    /**
     * data
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
     * Execute an API request for course enrollment
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
