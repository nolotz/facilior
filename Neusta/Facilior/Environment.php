<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 05.02.2016
 * Time: 07:59
 */

namespace Neusta\Facilior;


use Neusta\Facilior\Config\ConfigNotFoundException;
use Symfony\Component\Config\Definition\Exception\Exception;

class Environment
{

    /**
     * @var string
     */
    protected $username = '';

    /**
     * @var string
     */
    protected $database = '';

    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var bool
     */
    protected $sshTunnel = false;

    /**
     * @var string
     */
    protected $sshUsername = '';

    /**
     * @var string
     */
    protected $sshHost = '';

    /**
     * @var bool
     */
    protected $sshPrivateKey = false;

    /**
     * @var string
     */
    protected $sshPassword = '';


    public function __construct(array $config)
    {
        // Var Assignment
        $this->username = !empty($config['database']['username']) ? $config['database']['username'] : '';
        $this->password = !empty($config['database']['password']) ? $config['database']['password'] : '';
        $this->host = !empty($config['database']['host']) ? $config['database']['host'] : '';
        $this->database = !empty($config['database']['database']) ? $config['database']['database'] : '';

        $this->sshTunnel = !empty($config['ssh_tunnel']['enabled']) ? $config['ssh_tunnel']['enabled'] : false;
        $this->sshHost = !empty($config['ssh_tunnel']['host']) ? $config['ssh_tunnel']['host'] : '';
        $this->sshUsername = !empty($config['ssh_tunnel']['username']) ? $config['ssh_tunnel']['username'] : '';
        $this->sshPrivateKey = !empty($config['ssh_tunnel']['private_key']) ? $config['ssh_tunnel']['private_key'] : false;
        $this->sshPassword = !empty($config['ssh_tunnel']['password']) ? $config['ssh_tunnel']['password'] : '';
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param string $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return boolean
     */
    public function isSshTunnel()
    {
        return $this->sshTunnel;
    }

    /**
     * @param boolean $sshTunnel
     */
    public function setSshTunnel($sshTunnel)
    {
        $this->sshTunnel = $sshTunnel;
    }

    /**
     * @return string
     */
    public function getSshUsername()
    {
        return $this->sshUsername;
    }

    /**
     * @param string $sshUsername
     */
    public function setSshUsername($sshUsername)
    {
        $this->sshUsername = $sshUsername;
    }

    /**
     * @return string
     */
    public function getSshHost()
    {
        return $this->sshHost;
    }

    /**
     * @param string $sshHost
     */
    public function setSshHost($sshHost)
    {
        $this->sshHost = $sshHost;
    }

    /**
     * @return boolean
     */
    public function isSshPrivateKey()
    {
        return $this->sshPrivateKey;
    }

    /**
     * @param boolean $sshPrivateKey
     */
    public function setSshPrivateKey($sshPrivateKey)
    {
        $this->sshPrivateKey = $sshPrivateKey;
    }

    /**
     * @return string
     */
    public function getSshPassword()
    {
        return $this->sshPassword;
    }

    /**
     * @param string $sshPassword
     */
    public function setSshPassword($sshPassword)
    {
        $this->sshPassword = $sshPassword;
    }

    /**
     * @param $environmentName
     * @return Environment
     * @throws ConfigNotFoundException
     */
    public static function get($environmentName)
    {
        $environmentPath = getcwd() . '/.facilior/environments/' . $environmentName . '.yml';
        if(!file_exists($environmentPath)){
            throw new ConfigNotFoundException('Environment '.$environmentName.' couldnt be found!');
        }

        return new Environment(
            (new Config())->{$environmentName}()
        );
    }

    /**
     * @param $environmentName
     * @return mixed
     * @throws ConfigNotFoundException
     * @throws \Exception
     */
    public static function create($environmentName)
    {
        $fileHandle = file_put_contents(getcwd() . '/.facilior/environments/' . $environmentName . '.yml', self::defaultEnvironment($environmentName));
        if(!$fileHandle){
            throw new \Exception('Cant create environment.');
        }

        return self::get($environmentName);
    }

    /**
     * @param $environmentName
     * @return string
     */
    private static function defaultEnvironment($environmentName){
        return '#' . $environmentName . PHP_EOL
            . 'database:' . PHP_EOL
            . '  username: dummy' . PHP_EOL
            . '  password: dummy' . PHP_EOL
            . '  host: 127.0.0.1' . PHP_EOL
            . '  database: dummy' . PHP_EOL
            . 'ssh_tunnel:' . PHP_EOL
            . '  enabled: false' . PHP_EOL
            . '  username: dummy' . PHP_EOL
            . '  host: 127.0.0.1';
    }
}