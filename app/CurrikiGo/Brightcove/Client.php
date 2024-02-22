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
     * Brightcove API Type string 
     */
    private $type;
    
    /**
     * Create a new Client instance.
     * @param  object $setting
     * @param  string $type
     * @return void
     */
    public function __construct($setting, $type = '')
    {
        $this->setting = $setting;
        $this->type = $type;
    }

    /**
     * Run a Brightcove API Setting command
     * @param \App\CurrikiGo\Brightcove\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        $command->clientId = ( $this->type === 'media_catalog' ) ? $this->setting->client_key : $this->setting->client_id;
        $command->clientSecret = ( $this->type === 'media_catalog' ) ? $this->setting->secret_key : $this->setting->client_secret;
        return $command->execute();
    }
}
