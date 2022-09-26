<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching Course details in Canvas LMS
 */
class GetAssignmentGroupsCommand implements Command
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
     * Request queryString
     *
     * @var array
     */
    private $queryString;

    /**
     * Creates an instance of the command class
     *
     * @param int $courseId
     * @param string $queryString
     * @return void
     */
    public function __construct($courseId)
    {
        $this->courseId = $courseId;
        $this->endpoint = config('constants.canvas_api_endpoints.assignment_groups');
    }

    /**
     * Execute an API request for fetching course details
     *
     * @return string|null
     */
    public function execute()
    {
        $response = null;
        try {
            $response = $this->httpClient->request('GET', $this->apiURL . '/courses/' . $this->courseId . '/' . $this->endpoint . '?per_page=1000', [
                'headers' => ['Authorization' => "Bearer {$this->accessToken}"]
            ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {
        }

        return $response;
    }
}
