<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 04.02.2016
 * Time: 08:56
 */

namespace Neusta\Facilior\Command;


use Neusta\Facilior\Config;
use Neusta\Facilior\Console\ConsoleOutputInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractCommand extends Command
{

    protected $generalConfig = [];

    /**
     * @var ConsoleOutputInterface|null
     */
    protected $consoleOutput = null;

    /**
     * AbstractCommand constructor.
     * @param string $name
     */
    public function __construct($name = null)
    {
        $this->consoleOutput = new ConsoleOutputInterface();
        $this->generalConfig = (new Config())->general();
        parent::__construct($name);
    }

}