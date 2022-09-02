<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching courses in Canvas LMS
 */
class GetAllCoursesCommand implements Command
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
     * Creates an instance of the command class
     *
     * @param int $accountId
     * @param string $programName
     * @return void
     */
    public function __construct()
    {
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
            $url = $this->apiURL . '/courses' . '?per_page=1000';
            $response = $this->httpClient->request('GET', $url, [
                'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
            ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {
        }

        return $response;
    }
}
