<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

/**
 * This class handles fetching user data in Canvas LMS
 */
class GetUserCommand implements Command
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
     * User id
     *
     * @var int
     */
    public $userId;

    /**
     * Creates an instance of the command class
     *
     * @param int $userId
     * @return void
     */
    public function __construct($userId = '')
    {
        $this->userId = !empty($userId) ? $userId : 'self';
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
            $response = $this->httpClient->request('GET', $this->apiURL . '/users/' . $this->userId, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}"]
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}

        return $response;
    }
}
