<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Vimeo API end points
 * ClassName    Client
*/
namespace App\CurrikiGo\Vimeo;

use App\CurrikiGo\Vimeo\Contracts\Command;

class Client
{
    /**
     * Vimeo API Setting object 
     */
    private $setting;
    
    /**
     * Create a new Client instance.
     * @param  object $setting
     */
    public function __construct($setting)
    {
        $this->setting = $setting;
    }

    /**
     * Run a Vimeo API Setting command
     * @param \App\CurrikiGo\Vimeo\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        $command->accessToken = $this->setting->tool_secret_key;
        return $command->execute();
    }
}
