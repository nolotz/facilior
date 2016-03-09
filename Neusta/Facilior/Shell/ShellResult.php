<?php
namespace Neusta\Facilior\Shell;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 08.03.2016
 * Time: 15:24
 */
class ShellResult
{

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var int
     */
    protected $exitCode = 0;

    /**
     * @var string
     */
    protected $output = '';

    /**
     * ShellResult constructor.
     * @param string $command
     */
    public function __construct($command = '')
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $command
     */
    public function setCommand($command)
    {
        $this->command = $command;
    }

    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;
    }

    /**
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param string $output
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }
}
