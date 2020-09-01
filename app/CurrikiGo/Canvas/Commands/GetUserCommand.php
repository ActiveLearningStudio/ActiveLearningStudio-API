<?php

namespace App\CurrikiGo\Canvas\Commands;

use App\CurrikiGo\Canvas\Contracts\Command;

class GetUserCommand implements Command
{
    public $apiURL;
    public $accessToken;
    public $httpClient;

    public function execute()
    {
        $response = null;
        try {
            $response = $this->httpClient->request('GET', $this->apiURL . '/users/self', [
                    'headers' => ['Authorization' => "Bearer {$this->accessToken}"]
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}
