<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 04.02.2016
 * Time: 08:50
 */

namespace Neusta\Facilior\Console;


use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputInterface
{

    /**
     * @var null|string
     */
    protected $log = null;

    /**
     * @var null|string
     */
    protected $logFile = null;

    /**
     * @var null|ConsoleOutput
     */
    protected $consoleOutput = null;

    public function __construct($verbosity = ConsoleOutput::VERBOSITY_NORMAL, $decorated = null, OutputFormatterInterface $formatter = null)
    {
        $this->consoleOutput = new ConsoleOutput($verbosity, $decorated, $formatter);
    }

    /**
     * Prints a message to console
     *
     * @param $message
     * @param int $tabs
     * @param int $newLine
     */
    public function output($message, $tabs = 1, $newLine = 1)
    {
        //Auto logs to File
        $this->log(strip_tags($message));

        $this->consoleOutput->write(str_repeat("\t", $tabs));
        $this->consoleOutput->write($message); // Writes Message to console
        $this->consoleOutput->write(str_repeat(PHP_EOL, $newLine)); // New Lines
    }

    /**
     * Log a message to logfile
     * @param $message
     */
    public function log($message)
    {
        if($this->log === null){
            $this->logFile = realpath(getcwd() . '/.facilior/logs') . '/log-' . date('Ymd-His') . '.log';
            $this->log = fopen($this->logFile, 'w');
        }

        $message = date('d.m.Y H:i:s -- ') . $message;
        fwrite($this->log, $message . PHP_EOL);
    }

    /**
     * @return null|ConsoleOutput
     */
    public function getConsoleOutput()
    {
        return $this->consoleOutput;
    }

    /**
     * @param null|ConsoleOutput $consoleOutput
     */
    public function setConsoleOutput($consoleOutput)
    {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * @return null|string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * @return null|string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

}