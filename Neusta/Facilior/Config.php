<?php

namespace Neusta\Facilior;

use Neusta\Facilior\Config\ConfigNotFoundException;
use Symfony\Component\Yaml\Parser;

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
     *
     * @throws \Neusta\Facilior\Config\ConfigNotFoundException
     */
    public function __construct()
    {
        $this->configParser = new Parser();
        $this->initGeneralConfig(); // Loads general config
    }

    /**
     * Returns General Config
     *
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
                throw new ConfigNotFoundException('Cannot find the environment ' . $environment);
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

        $config = $this->configParser->parse(file_get_contents($filePath));
        return $this->replaceEnvVars($config);
    }

    /**
     * Replace env vars in yaml
     *
     * @param array $config
     * @return array
     */
    private function replaceEnvVars(array $config): array
    {
        foreach ($config as $k => $v) {
            if ($this->isEnvPlaceholder($v)) {
                $config[$k] = $this->getValueFromEnv($v);
            } elseif (\is_array($v)) {
                $config[$k] = $this->replaceEnvVars($v);
            }
        }
        return $config;
    }

    /**
     * Checks if a value is a string and contains an env placeholder
     * Taken from TYPO3 Core YamlFileLoader
     *
     * @param mixed $value the probe to check for
     * @return bool
     */
    protected function isEnvPlaceholder($value): bool
    {
        return \is_string($value) && (strpos($value, '%env(') !== false);
    }

    /**
     * Return value from environment variable
     * Environment variables may only contain word characters and underscores (a-zA-Z0-9_)
     * to be compatible to shell environments.
     * Taken from TYPO3 Core YamlFileLoader
     *
     * @param string $value
     * @return string
     */
    protected function getValueFromEnv(string $value): string
    {
        $matched = preg_match('/%env\([\'"]?(\w+)[\'"]?\)%/', $value, $matches);
        if ($matched === 1) {
            $envVar = getenv($matches[1]);
            $value = $envVar ? str_replace($matches[0], $envVar, $value) : $value;
        }
        return $value;
    }
}