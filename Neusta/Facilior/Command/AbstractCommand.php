<?php
namespace Neusta\Facilior\Command;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 04.02.2016
 * Time: 08:56
 */


use Neusta\Facilior\Config;
use Neusta\Facilior\Services\ConsoleService;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{

    protected $generalConfig = [];

    /**
     * @var ConsoleService|null
     */
    protected $consoleOutput = null;

    /**
     * AbstractCommand constructor.
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->consoleOutput = new ConsoleService();
        $this->generalConfig = (new Config())->general();
        parent::__construct($name);
    }

}