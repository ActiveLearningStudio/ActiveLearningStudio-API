<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class GetUserCommand implements Command
{
    public $api_url;
    public $access_token;
    public $http_client;

    public function execute()
    {
        $response = null;
        try {
            $response = $this->http_client->request('GET', $this->api_url.'/users/self', [
                    'headers' => ['Authorization' => "Bearer {$this->access_token}"]
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}