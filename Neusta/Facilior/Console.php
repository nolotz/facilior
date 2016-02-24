<?php

namespace Neusta\Facilior;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:17
 */

use Neusta\Facilior\Console\ConsoleService;
use Neusta\Facilior\Console\Kernel;
use Symfony\Component\Console\Application;

class Console
{
    /**
     * @var Kernel|null
     */
    protected $kernel = null;

    /**
     * @var null|Application
     */
    protected $application = null;

    /**
     * @var null|ConsoleService
     */
    protected $console = null;


    /**
     * Console constructor.
     */
    public function __construct()
    {
        $this->kernel = new Kernel();
        $this->application = new Application('Neusta Facilior', FACILIOR_VERSION);
        $this->application->setAutoExit(false);

        //Creates Console Output
        $this->console = new ConsoleService();

        //Loads Commands into Application
        $this->application->addCommands($this->kernel->commands());
    }

    /**
     * Main function of Facilior
     * @return int
     */
    public function execute()
    {
        //Greetings
        $this->console->log('Logging started');
        $this->console->output('<fg=default;options=bold>Logging started:</> <fg=magenta>' .
            $this->console->getLogFile() . '</>', 0, 2);

        //Run Command and check if there is a config error
        $exitCode = $this->application->run();

        return $exitCode;
    }
}
