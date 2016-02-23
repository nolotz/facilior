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
    public function general($key = null)
    {
        if (empty($key)) {
            return $this->generalConfig;
        }

        return !empty($this->generalConfig[$key]) ? $this->generalConfig[$key] : '';
    }

    /**
     * @param $environment
     * @param $arguments
     * @return array|string
     * @throws ConfigNotFoundException
     */
    public function __call($environment, $arguments)
    {
        if (empty($this->environments[$environment])) {
            $filePath = getcwd() . '/.facilior/environments/' . $environment . '.yml';
            if (!file_exists($filePath)) {
                throw new ConfigNotFoundException('Connot find the environment ' . $environment);
            }

            $this->environments[$environment] = $this->parseConfigFile($filePath);
        }

        if (!empty($this->environments[$environment])) {
            if (empty($arguments[0])) {
                return $this->environments[$environment];
            }

            $key = strtolower($arguments[0]);
            return !empty($this->environments[$environment][$key]) ? $this->environments[$environment][$key] : '';
        }

        return '';
    }

    /**
     * @throws ConfigNotFoundException
     */
    protected function initGeneralConfig()
    {
        $filePath = getcwd() . '/.facilior/general.yml';
        $this->generalConfig = file_exists($filePath) ? $this->parseConfigFile($filePath) : [];
    }

    /**
     * @param $filePath
     * @return mixed
     * @throws ConfigNotFoundException
     */
    protected function parseConfigFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new ConfigNotFoundException('Cannot find the file at path ' . $filePath);
        }

        return $this->configParser->parse(file_get_contents($filePath));
    }
}