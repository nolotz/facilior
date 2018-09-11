<?php
namespace Neusta\Facilior\Tests\Console;

use Neusta\Facilior\Services\ConsoleService;
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
    public function testOutputWillLogToFile()
    {
        $echoMock = $this->mockBuilder->setName('fwrite')
            ->setNamespace('Symfony\Component\Console\Output')
            ->setFunction(function () {
            })
            ->build();
        $echoMock->enable();

        $serviceMock = $this->getMockBuilder(ConsoleService::class)->setMethods(['log'])->getMock();
        $serviceMock->expects($this->once())
            ->method('log');

        $serviceMock->output('Test');
    }
}
