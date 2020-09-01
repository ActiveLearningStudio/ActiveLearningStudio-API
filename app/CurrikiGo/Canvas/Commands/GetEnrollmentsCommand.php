<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class GetEnrollmentsCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;

    public function __construct($user)
    {
        $this->user = $user;
    }

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
