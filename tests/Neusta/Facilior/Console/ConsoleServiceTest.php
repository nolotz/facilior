<?php
namespace Neusta\Facilior\Tests\Console;

use Neusta\Facilior\Console\ConsoleService;
use Neusta\Facilior\Tests\BaseTest;
use phpmock\MockBuilder;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 23.02.2016
 * Time: 09:16
 */
class ConsoleServiceTest extends BaseTest
{

    /**
     * @var null|ConsoleService
     */
    protected $consoleService = null;

    /**
     * @var null|MockBuilder
     */
    protected $mockBuilder = null;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->consoleService = new ConsoleService();
        $this->mockBuilder = new MockBuilder();
    }

    /**
     * @test
     * @return void
     */
    public function testServiceCreateNewLogFileIfNotExists()
    {
        $fwriteMock = $this->mockBuilder->setName('fwrite')
            ->setNamespace('Neusta\Facilior\Console')
            ->setFunction(function () {
                return 1;
            })
            ->build();

        $fwriteMock->enable();

        $serviceMock = $this->getMock('\Neusta\Facilior\Console\ConsoleService', array('setUpLogFile'));
        $serviceMock->expects($this->once())
            ->method('setUpLogFile');

        $serviceMock->log("Test");
    }

    /**
     * @test
     * @return void
     */
    public function testOutputWillLogToFile()
    {
        $echoMock = $this->mockBuilder->setName('fwrite')
            ->setNamespace('Symfony\Component\Console\Output')
            ->setFunction(function () {
            })
            ->build();
        $echoMock->enable();

        $serviceMock = $this->getMock('\Neusta\Facilior\Console\ConsoleService', array('log'));
        $serviceMock->expects($this->once())
            ->method('log');

        $serviceMock->output('Test');
    }
}
