<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:17
 */

namespace Neusta\Facilior;


use Neusta\Facilior\Console\Kernel;
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
     * Console constructor.
     */
    public function __construct()
    {
        $this->kernel = new Kernel();
        $this->application = new Application('Neusta Facilior', FACILIOR_VERSION);

        //Loads Commands into Application
        $this->application->addCommands($this->kernel->commands());
    }

    /**
     * Main function of Facilior
     * @param null $arguments
     * @return exitCode
     */
    public function execute($arguments = null)
    {
        $argumentsInput = new ArrayInput($arguments);
        $outputInterface = new ConsoleOutput();

        $this->application->run($argumentsInput, $outputInterface);
    }



}