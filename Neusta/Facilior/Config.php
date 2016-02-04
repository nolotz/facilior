<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:06
 */

namespace Neusta\Facilior;


use Neusta\Facilior\Config\ConfigNotFoundException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class Config
{
    /**
     * @var null|Parser
     */
    protected $configParser = null;

    /**
     * @var array
     */
    protected $generalConfig = [];

    /**
     * @var array
     */
    protected $environments = [];

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->configParser = new Parser();
        $this->initGeneralConfig(); // Loads general config
    }

    /**
     * Returns General Config
     * @param $key
     * @return mixed
     */
    public function general($key)
    {
        if(empty($key)){
            throw new \InvalidArgumentException('Invalid key is given.');
        }

        return !empty($this->generalConfig[$key]) ? $this->generalConfig[$key] : '';
    }

    /**
     * @param $environment
     * @param $arguments
     * @return $this|string
     * @throws ConfigNotFoundException
     */
    public function __call($environment, $arguments)
    {

        if(empty($arguments[0])){
           throw new \InvalidArgumentException('Invalid environment is given.');
        }

        $key = $arguments[0];

        if(!empty($this->environments[$environment])){
            return !empty($this->environments[$environment][$key]) ? $this->environments[$environment][$key] : '';
        }

        $filePath = getcwd() . '/.facilior/environments/' . $environment . '.yml';

        if(!file_exists($filePath)){
            throw new ConfigNotFoundException('Connot find the environment $environment');
        }
        $this->environments[$environment] = $this->parseConfigFile($filePath);

        return $this;
    }

    /**
     * @throws ConfigNotFoundException
     */
    protected function initGeneralConfig()
    {
        $this->generalConfig = $this->parseConfigFile(getcwd() . '/.facilior/general.yml');
    }

    /**
     * @param $filePath
     * @return mixed
     * @throws ConfigNotFoundException
     */
    protected function parseConfigFile($filePath)
    {
        if(!file_exists($filePath)) {
            throw new ConfigNotFoundException('Cannot find the file at path $filePath');
        }

        return $this->configParser->parse(file_get_contents($filePath));
    }
}