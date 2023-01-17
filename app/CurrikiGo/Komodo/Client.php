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
     * Create a new Client instance.
     */
    public function __construct()
    {

    }

    /**
     * Run a Komodo API Setting command
     * @param \App\CurrikiGo\Komodo\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        return $command->execute();
    }
}
