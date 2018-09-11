<?php
namespace Neusta\Facilior\Tests;

use Neusta\Facilior\Environment;


class EnvironmentTest extends BaseTest
{

    public function testCreateMethodWillThrowExceptionWhenNameIsEmpty()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(1456222301);
        $this->expectExceptionMessage('EnvironmentName cannot be empty.');
        Environment::create('');
    }

    public function testEnvironmentWillAssignVars()
    {
        $config = array(
            'database' => array(
                'username' => 'username',
                'password' => 'password',
                'host' => 'host',
                'database' => 'database',
                'port' => 'port'
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
        $this->assertSame('port', $env->getPort());

        $this->assertSame('username', $env->getSshUsername());
        $this->assertSame('host', $env->getSshHost());
        $this->assertTrue($env->isSshTunnel());
    }


}
