<?php
namespace App\CurrikiGo\Canvas\Commands;
use App\CurrikiGo\Canvas\Contracts\Command;

class GetCoursesCommand implements Command
{
    public $api_url;
    public $access_token;
    public $http_client;
    private $account_id;
    private $program_name;

    public function __construct($account_id, $program_name = null) {
        $this->account_id = $account_id;
        $this->program_name = $program_name;
    }

    public function execute()
    {
        $response = null;
        try {
            $url = $this->api_url.'/accounts/'.$this->account_id.'/courses';
            $url .= $this->program_name ? '?search_term='.$this->program_name : '';
            $response = $this->http_client->request('GET', $url, [
                    'headers' => ['Authorization' => "Bearer {$this->access_token}", 'Accept' => 'application/json']
                ])->getBody()->getContents();
            $response = json_decode($response);
        } catch (Exception $ex) {}
        
        return $response;
    }
}