<?php
namespace Neusta\Facilior\Services;

use Neusta\Facilior\Environment;
use Neusta\Facilior\Shell\ShellResult;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 26.02.2016
 * Time: 12:27
 */

class ShellService
{
    /**
     * @var ConsoleService
     */
    protected $consoleService;

    /**
     * ShellService constructor.
     */
    public function __construct()
    {
        $this->consoleService = new ConsoleService();
    }

    /**
     * @param $command
     * @param $arguments
     * @return ShellResult
     * @throws \Exception
     */
    public function execute($command, $arguments)
    {
        $shellCommand = $this->mapArguments($command, $arguments);
        $shellResult = new ShellResult($shellCommand);

        exec($shellCommand, $output, $exitCode);

        $shellResult->setExitCode($exitCode);
        $shellResult->setOutput($output);

        $this->consoleService->log('Executing: ' . $shellResult->getCommand());
        $this->consoleService->log('Output:' . implode(PHP_EOL, $output));

        if ($exitCode != 0) {
            throw new \Exception("Error in Execution. Please check your Logs");
        }

        return $shellResult;
    }

    /**
     * @param $commandString
     * @param $arguments
     * @return mixed
     */
    protected function mapArguments($commandString, $arguments)
    {
        foreach ($arguments as $key => $argument) {
            $commandString = str_replace('##' . $key . '##', $argument, $commandString);
        }

        return $commandString;
    }
}
