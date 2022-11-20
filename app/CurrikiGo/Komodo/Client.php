<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Komodo API end points
 * ClassName    Client
*/
namespace App\CurrikiGo\Komodo;

use App\CurrikiGo\Komodo\Contracts\Command;

class Client
{
    /**
     * Komodo API Setting object 
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
     * Run a Komodo API Setting command
     * @param \App\CurrikiGo\Komodo\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        $command->accessToken = $this->setting->tool_secret_key;
        return $command->execute();
    }
}
