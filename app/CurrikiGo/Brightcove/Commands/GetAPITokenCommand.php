<?php
/**
 * @Author      Asim Sarwar
 * Date         16-12-2021
 * Description  Handle get api token in Brightcove
 * ClassName    GetAPITokenCommand
*/
namespace App\CurrikiGo\Brightcove\Commands;

use App\Exceptions\GeneralException;
use App\CurrikiGo\Brightcove\Contracts\Command;
use Illuminate\Support\Facades\Http;

class GetAPITokenCommand implements Command
{
    /**
     * Client Id
     * @var string
     */
    public $clientId;

    /**
     * Client Secret
     * @var string
     */
    public $clientSecret;
    
    /**
     * Brightcove API Settings
     * 
     * @var object
     */
    private $setting;

    /**
     * Creates an instance of the GetAPITokenCommand class.
     * @param object $setting
     * @return void
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    /**
     * Execute an API request to return Brigcove API token
     * @return array
     * @throws GeneralException
     */
    public function execute()
    {
        $apiUrl = config('brightcove-api.token_url');
        $requestParam = '?grant_type=client_credentials&client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret . '';
        $response = Http::post($apiUrl . $requestParam);
        if ($response->status() == 200) {
            $result = $response->json();
            return array('Authorization' => ' Bearer ' . $result['access_token']);
        }
        throw new GeneralException('Brightcove api token not found.Please try later!');
    }

}
