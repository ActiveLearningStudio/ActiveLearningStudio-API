<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching Course details in Canvas LMS
 */
class CreateAssignmentGroupsCommand implements Command
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
    private $assignmentGroupName;

    /**
     * Creates an instance of the command class
     *
     * @param int $courseId
     * @param string $queryString
     * @return void
     */
    public function __construct($courseId, $assignmentGroupName)
    {
        $this->courseId = $courseId;
        $this->endpoint = config('constants.canvas_api_endpoints.assignment_groups');
        $this->courseData = $this->prepareCourseData($assignmentGroupName);
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
            $response = $this->httpClient->request('POST', $this->apiURL . '/courses/' . $this->courseId . '/' . $this->endpoint, [
                'headers' => ['Authorization' => "Bearer {$this->accessToken}"],
                'json' => $this->courseData
            ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {
        }

        return $response;
    }

    /**
     * Prepare course data for API payload
     * 
     * @param array $data
     * @return array
     */
    public function prepareCourseData($assignmentGroupName)
    {
        return ["name" => $assignmentGroupName];
    }
}
