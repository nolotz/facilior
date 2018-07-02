<?php
namespace Neusta\Facilior\Tests;

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase
{
    /**
     * Disable logging to log file and turn off colors
     *
     * @before
     */
    protected function setUpConsoleStatics()
    {
        $consoleReflection = new \ReflectionClass('Neusta\Facilior\Services\ConsoleService');
        $logEnableProperty = $consoleReflection->getProperty('logEnabled');
        $logEnableProperty->setAccessible(true);
        $logEnableProperty->setValue($consoleReflection, false);
    }
}