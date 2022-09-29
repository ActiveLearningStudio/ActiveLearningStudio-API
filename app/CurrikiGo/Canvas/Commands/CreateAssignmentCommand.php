<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching Course details in Canvas LMS
 */
class CreateAssignmentCommand implements Command
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
     * Request Assignment Group Id
     *
     * @var array
     */
    private $assignmentGroupId;
    /**
     * Request new assignment name
     *
     * @var array
     */
    private $assignmentName;
    /**
     * Id of activity in curriki
     *
     * @var array
     */
    private $currikiActivityId;

    /**
     * Creates an instance of the command class
     *
     * @param int $courseId
     * @param string $queryString
     * @return void
     */
    public function __construct($courseId, $assignmentGroupId, $assignmentName, $currikiActivityId)
    {
        $this->courseId = $courseId;
        $this->assignmentGroupId = $assignmentGroupId;
        $this->assignmentName = $assignmentName;
        $this->currikiActivityId = $currikiActivityId;
        $this->endpoint = config('constants.canvas_api_endpoints.create_assignment');
        $this->courseData = $this->prepareCourseData($assignmentGroupId, $assignmentName, $this->currikiActivityId);
    }

    /**
     * Execute an API request for fetching course details
     *
     * @return string|null
     */
    public function execute()
    {
        $assignment = $this->courseData;
        $response = null;
        try {
            $response = $this->httpClient->request('POST', $this->apiURL . '/courses/' . $this->courseId . '/' . $this->endpoint, [
                'headers' => ['Authorization' => "Bearer {$this->accessToken}"],
                'json' => ['assignment' => $assignment]
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
    public function prepareCourseData($assignmentGroupId, $assignmentGroupName, $currikiActivityId)
    {
        $assignment = [];
        $assignment["name"] = $assignmentGroupName;
        $assignment['assignment_group_id'] = $assignmentGroupId;
        $assignment['self_signup'] = 'enabled';
        $assignment['position'] = 1;
        $assignment['submission_types'][] = 'external_tool';
        $assignment['return_type'] = 'lti_launch_url';
        $assignment['workflow_state'] = 'published';
        $assignment['points_possible'] = 100;
        $assignment['published'] = true;
        $assignment['external_tool_tag_attributes']['url'] = config('constants.curriki-tsugi-host') . "?activity=" . $currikiActivityId;
        return $assignment;
    }
}
