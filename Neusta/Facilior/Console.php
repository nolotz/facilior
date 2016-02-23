<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:17
 */

namespace Neusta\Facilior;


use Neusta\Facilior\Console\ConsoleService;
use Neusta\Facilior\Console\Kernel;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param null $arguments
     * @return int
     */
    public function execute($arguments = null)
    {
        //Defining internal variables
        $exitCode = 0;

        //Greetings
        $this->console->log('Logging started');
        $this->console->output('<fg=default;options=bold>Logging started:</> <fg=magenta>' .
            $this->console->getLogFile() . '</>', 0, 2);

        //Run Command and check if there is a config error
        $exitCode = $this->application->run();

        return $exitCode;
    }


    /**
     * Overrides symfonys default commands
     * @return void
     */
    protected function applyApplicationConfiguration()
    {
        $this->application->setDefaultCommand('');
        $this->application->setCatchExceptions(false);
    }

}