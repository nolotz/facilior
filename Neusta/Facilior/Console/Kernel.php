<?php
namespace Neusta\Facilior\Console;
use Neusta\Facilior\Command\InitCommand;
use Neusta\Facilior\Command\PullCommand;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 02.02.2016
 * Time: 16:18
 */
class Kernel
{

    /**
     * Returns all Commands for Console
     * @return array
     */
    public static function commands(){
        return [
            new InitCommand(),
            new PullCommand()
        ];
    }
}