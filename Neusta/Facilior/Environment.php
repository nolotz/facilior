<?php
namespace Neusta\Facilior;

use Neusta\Facilior\Config\ConfigNotFoundException;

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
    protected $port = '';

    /**
     * @var string
     */
    protected $password = '';

    /**
     * @var bool
     */
    protected $singleTransaction = false;

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


    public function __construct(array $config)
    {
        $this->username = $this->getDatabaseSetting($config, 'username');
        $this->password = $this->getDatabaseSetting($config, 'password');
        $this->host = $this->getDatabaseSetting($config, 'host');
        $this->port = $this->getDatabaseSetting($config, 'port');
        $this->database = $this->getDatabaseSetting($config, 'database');
        $this->singleTransaction = (bool)$this->getDatabaseSetting($config, 'single_transaction', false);

        $this->sshTunnel = $this->getSshSetting($config, 'enabled', false);
        $this->sshHost = $this->getSshSetting($config, 'host', '');
        $this->sshUsername = $this->getSshSetting($config, 'username', '');
    }

    /**
     * @param $config
     * @param $key
     * @param string $defaultValue
     * @return string
     */
    protected function getDatabaseSetting($config, $key, $defaultValue = '')
    {
        $databaseSettingValue = $defaultValue;
        if (isset($config['database'][$key])) {
            $databaseSettingValue = $config['database'][$key];
        }

        return $databaseSettingValue;
    }

    /**
     * @param $config
     * @param $key
     * @param string $defaultValue
     * @return string
     */
    protected function getSshSetting($config, $key, $defaultValue = '')
    {
        $sshSettingValue = $defaultValue;
        if (isset($config['ssh_tunnel'][$key])) {
            $sshSettingValue = $config['ssh_tunnel'][$key];
        }

        return $sshSettingValue;
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
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
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
     * @return bool
     */
    public function isSingleTransaction(): bool
    {
        return $this->singleTransaction;
    }

    /**
     * @param bool $singleTransaction
     */
    public function setSingleTransaction(bool $singleTransaction): void
    {
        $this->singleTransaction = $singleTransaction;
    }

    /**
     * @param $environmentName
     * @return Environment
     * @throws ConfigNotFoundException
     */
    public static function get($environmentName)
    {
        $environmentPath = getcwd() . '/.facilior/environments/' . $environmentName . '.yml';
        if (!file_exists($environmentPath)) {
            throw new ConfigNotFoundException('Environment ' . $environmentName . ' couldnt be found!');
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
        if (empty($environmentName)) {
            throw new \Exception('EnvironmentName cannot be empty.', 1456222301);
        }

        $fileHandle = file_put_contents(getcwd() . '/.facilior/environments/' .
            $environmentName . '.yml', self::defaultEnvironment($environmentName));
        if (!$fileHandle) {
            throw new \Exception('Cant create environment.');
        }

        return self::get($environmentName);
    }

    /**
     * @param $environmentName
     * @return string
     */
    protected static function defaultEnvironment($environmentName)
    {
        return '#' . $environmentName . PHP_EOL
        . 'database:' . PHP_EOL
        . '  username: dummy' . PHP_EOL
        . '  password: dummy' . PHP_EOL
        . '  host: 127.0.0.1' . PHP_EOL
        . '  database: dummy' . PHP_EOL
        . '  port: 3306' . PHP_EOL
        . 'ssh_tunnel:' . PHP_EOL
        . '  enabled: false' . PHP_EOL
        . '  username: dummy' . PHP_EOL
        . '  host: 127.0.0.1';
    }
}
