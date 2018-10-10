<?php

namespace Neusta\Facilior;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:17
 */

use Dotenv\Dotenv;
use Neusta\Facilior\Console\Kernel;
use Neusta\Facilior\Services\ConsoleService;
use Neusta\Facilior\Services\FileService;
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
     * Services constructor.
     */
    public function __construct()
    {
        $this->kernel = new Kernel();
        $this->application = new Application('Neusta Facilior', FACILIOR_VERSION);
        $this->application->setAutoExit(false);

        //Creates Services Output
        $this->console = new ConsoleService();

        //Loads Commands into Application
        $this->application->addCommands($this->kernel->commands());
    }

    /**
     * Main function of Facilior
     *
     * @return int
     * @throws \Exception
     */
    public function execute()
    {
        if(!file_exists(getcwd() . '/.facilior/logs/')) {
            $this->console->logEnabled = false;
            $this->console->log('Logging started');
            $this->console->output('<fg=default;options=bold>Logging started:</> <fg=magenta>' .
            $this->console->getLogFile() . '</>', 0, 2);
        }

        $dotenv = new Dotenv(getcwd());
        $dotenv->load();

        $fileService = new FileService();
        $fileService->init();

        //Run Command and check if there is a config error
        $exitCode = $this->application->run();
        $fileService->cleanup();

        return $exitCode;
    }
}
