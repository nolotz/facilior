<?php
namespace Neusta\Facilior\Console;

use Neusta\Facilior\Command\DatabaseUpdateCommand;
use Neusta\Facilior\Command\FilesGetCommand;
use Neusta\Facilior\Command\InitCommand;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 02.02.2016
 * Time: 16:18
 */
class Kernel
{

    /**
     * Returns all Commands for Services
     * @return array
     */
    public static function commands()
    {
        return [
            new InitCommand(),
            new DatabaseUpdateCommand(),
            new FilesGetCommand()
        ];
    }
}
