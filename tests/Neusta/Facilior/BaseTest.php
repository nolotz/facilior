<?php
namespace Neusta\Facilior\Tests;

abstract class BaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Disable logging to log file and turn off colors
     *
     * @before
     */
    protected function setUpConsoleStatics()
    {
        $consoleReflection = new \ReflectionClass('Neusta\Facilior\Console\ConsoleService');
        $logEnableProperty = $consoleReflection->getProperty('logEnabled');
        $logEnableProperty->setAccessible(true);
        $logEnableProperty->setValue($consoleReflection, false);
    }
}