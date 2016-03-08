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
     * @var int
     */
    protected $lastExitCode = 0;

    /**
     * @var string
     */
    protected $lastOutput = "";

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

        $this->lastExitCode = $exitCode;
        $this->lastOutput = $output;

        $shellResult->setExitCode($exitCode);
        $shellResult->setResult($output);


        if ($exitCode != 0) {
            throw new \Exception("Error in Execution.");
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
            $argument = $this->prepareArgument($argument);
            $commandString = str_replace($key, $argument, $commandString);
        }

        return $commandString;
    }

    /**
     * @param $argument
     * @return string
     */
    protected function prepareArgument($argument)
    {
        return '##' . escapeshellarg($argument) . '##';
    }

    /**
     * @return int
     */
    public function getLastExitCode()
    {
        return $this->lastExitCode;
    }

    /**
     * @param int $lastExitCode
     */
    public function setLastExitCode($lastExitCode)
    {
        $this->lastExitCode = $lastExitCode;
    }

    /**
     * @return string
     */
    public function getLastOutput()
    {
        return $this->lastOutput;
    }

    /**
     * @param string $lastOutput
     */
    public function setLastOutput($lastOutput)
    {
        $this->lastOutput = $lastOutput;
    }

}
