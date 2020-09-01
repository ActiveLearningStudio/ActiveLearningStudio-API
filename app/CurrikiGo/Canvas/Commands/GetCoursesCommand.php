<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class GetCoursesCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;
    private $accountId;
    private $programName;

    public function __construct($accountId, $programName = null)
    {
        $this->accountId = $accountId;
        $this->programName = $programName;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->apiURL . '/accounts/' . $this->accountId . '/courses';
            $url .= $this->programName ? '?search_term=' . $this->programName : '';
            $response = $this->httpClient->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
