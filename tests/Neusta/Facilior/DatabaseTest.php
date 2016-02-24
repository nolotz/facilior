<?php
namespace Neusta\Facilior\Tests;

use Neusta\Facilior\Environment;
use phpmock\MockBuilder;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 23.02.2016
 * Time: 11:28
 */



class DatabaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var null|Environment
     */
    protected $environment = null;

    /**
     * @SetUp
     * @return void
     */
    public function setUp()
    {
        $this->environment = new Environment(
            array(
                'database' => array(
                    'username' => 'username',
                    'password' => 'password',
                    'host' => 'host',
                    'database' => 'database',
                ),
                'ssh_tunnel' => array(
                    'enabled' => true,
                    'host' => 'host',
                    'username' => 'username',
                )
            )
        );
    }

    /**
     * @return void
     */
    public function testExportSqlWillCreateTempFile()
    {
        $databaseMock = $this->getMock(
            '\Neusta\Facilior\Database',
            array('createTempFile', 'tunneledDatabaseExport', 'databaseExport'),
            array($this->environment)
        );

        $databaseMock->expects($this->once())
            ->method('createTempFile');

        $databaseMock->exportSql();
    }

    /**
     * @test
     * @return void
     * @dataProvider exportSqlWillUseTheRightMethodDataProvider
     * @param $environment
     * @param $functionName
     */
    public function testExportSqlWillUseTheRightMethod($environment, $functionName)
    {
        $databaseMock = $this->getMock(
            '\Neusta\Facilior\Database',
            array('createTempFile', 'tunneledDatabaseExport', 'databaseExport'),
            array($environment)
        );

        $databaseMock->expects($this->once())
            ->method($functionName);
        $databaseMock->exportSql();
    }

    /**
     * Provider for testExportSqlWillUseTheRightMethod
     * @return array
     */
    public function exportSqlWillUseTheRightMethodDataProvider()
    {
        $tunneledEnvironment = new Environment(
            array(
                'database' => array(
                    'username' => 'username',
                    'password' => 'password',
                    'host' => 'host',
                    'database' => 'database',
                ),
                'ssh_tunnel' => array(
                    'enabled' => true,
                    'host' => 'host',
                    'username' => 'username',
                )
            )
        );

        $noneTunneledEnv = clone $tunneledEnvironment;
        $noneTunneledEnv->setSshTunnel(false);


        return array(
            'tunneled' => array($tunneledEnvironment, 'tunneledDatabaseExport'),
            'noneTunneled' => array($noneTunneledEnv, 'databaseExport')
        );
    }


    /**
     * @test
     * @return void
     * @dataProvider importSqlWillUseTheRightMethodDataProvider
     * @param $environment
     * @param $functionName
     */
    public function testImportSqlWillUseTheRightMethod($environment, $functionName)
    {
        $databaseMock = $this->getMock(
            '\Neusta\Facilior\Database',
            array('tunneledDatabaseImport', 'databaseImport'),
            array($environment)
        );

        $mockBuilder = new MockBuilder();
        $fileExistsMock = $mockBuilder->setName('file_exists')
            ->setNamespace('Neusta\Facilior')
            ->setFunction(function () {
                return 1;
            })
            ->build();

        $fileExistsMock->disable(); // Work around
        $fileExistsMock->enable();


        $databaseMock->expects($this->once())
            ->method($functionName);
        $databaseMock->importSql('Test.sql');
        $fileExistsMock->disable();
    }

    /**
     * Provider for testExportSqlWillUseTheRightMethod
     * @return array
     */
    public function importSqlWillUseTheRightMethodDataProvider()
    {
        $tunneledEnvironment = new Environment(
            array(
                'database' => array(
                    'username' => 'username',
                    'password' => 'password',
                    'host' => 'host',
                    'database' => 'database',
                ),
                'ssh_tunnel' => array(
                    'enabled' => true,
                    'host' => 'host',
                    'username' => 'username',
                )
            )
        );

        $noneTunneledEnv = clone $tunneledEnvironment;
        $noneTunneledEnv->setSshTunnel(false);


        return array(
            'tunneled' => array($tunneledEnvironment, 'tunneledDatabaseImport'),
            'noneTunneled' => array($noneTunneledEnv, 'databaseImport')
        );
    }

    /**
     * @test
     * @return void
     */
    public function testImportWillThrowExceptionOnEmptyPath()
    {
        $this->setExpectedException('\Exception', 'File not exists.', 1456234084);

        $databaseMock = $this->getMock(
            '\Neusta\Facilior\Database',
            array('tunneledDatabaseImport', 'databaseImport'),
            array($this->environment)
        );

        $databaseMock->importSql('Test');
    }

}
