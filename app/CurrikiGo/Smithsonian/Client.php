<?php
/**
 * @Author      Asim Sarwar
 * Description  Handle Smithsonian Institution Open Access API end points
 * ClassName    Client
*/
namespace App\CurrikiGo\Smithsonian;

use App\CurrikiGo\Smithsonian\Contracts\Command;

class Client
{    
    /**
     * Client constructor.
     */
    public function __construct()
    {
    }

    /**
     * Run a Smithsonian Institution Open Access API command
     * @param \App\CurrikiGo\Smithsonian\Contracts\Command $command
     * @return null|Response
     */
    public function run(Command $command)
    {
        return $command->execute();
    }
}
