<?php
namespace Neusta\Facilior\Tests;

use Neusta\Facilior\Environment;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 23.02.2016
 * Time: 10:51
 */

class EnvironmentTest extends BaseTest
{

    /**
     * @test
     * @return void
     */
    public function testCreateMethodWillThrowExceptionWhenNameIsEmpty()
    {
        $this->setExpectedException('\Exception', 'EnvironmentName cant be empty.', 1456222301);
        Environment::create('');
    }

    /**
     * @test
     * @return void
     */
    public function testEnvironmentWillAssignVars()
    {
        $config = array(
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
        );

        $env = new Environment($config);
        $this->assertSame('username', $env->getUsername());
        $this->assertSame('password', $env->getPassword());
        $this->assertSame('host', $env->getHost());
        $this->assertSame('database', $env->getDatabase());

        $this->assertSame('username', $env->getSshUsername());
        $this->assertSame('host', $env->getSshHost());
        $this->assertTrue($env->isSshTunnel());
    }


}
