<?php
/**
 * @Author      Asim Sarwar
 * Date         16-12-2021
 * Description  Handle Brightcove API end points
 * ClassName    Client
*/
namespace App\CurrikiGo\Brightcove;

use App\CurrikiGo\Brightcove\Contracts\Command;

class Client
{
    /**
     * Brightcove API Setting object 
     */
    private $setting;
    
    /**
     * Create a new Client instance.
     * @param  object $setting
     * @return void
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    /**
     * Run a Brightcove API Setting command
     * @param \App\CurrikiGo\Brightcove\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        $command->clientId = $this->setting->client_id;
        $command->clientSecret = $this->setting->client_secret;
        return $command->execute();
    }
}
