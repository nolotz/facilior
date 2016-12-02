<?php
/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Noah-Jerome Lotzer <n.lotzer@neusta.de>, Neusta GmbH
 *
 *  All rights reserved
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

namespace Nolotz\Facilior\Console;


use Symfony\Component\Translation\Exception\NotFoundResourceException;

class EnvironmentFactory
{
    const ENVIRONMENT_PATH = '/.facilior/environments/';

    /**
     * @param string $environment
     *
     * @return Environment
     */
    public static function make($environment) {
        return (new EnvironmentFactory())->get($environment);
    }

    /**
     * get
     *
     * @param string $environment
     * @return Environment
     */
    protected function get($environment)
    {
        if(!$this->checkEnvironmentExists($environment)) {
            throw new NotFoundResourceException('Environment ' . $environment .' not found');
        }

        $environment = $this->parseYaml($environment);
        return Environment::create($environment);
    }

    /**
     * parseYaml
     *
     * @param $environment
     *
     * @return mixed
     */
    protected function parseYaml($environment)
    {
        $file = $this->getAbsoluteFile($environment);
        return \Symfony\Component\Yaml\Yaml::parse(
            file_get_contents($file)
        );
    }

    /**
     * checkEnvironmentExists
     * @param $environment
     *
     * @return bool
     */
    protected function checkEnvironmentExists($environment)
    {
        return file_exists($this->getAbsoluteFile($environment));
    }

    /**
     * @param $environment
     *
     * @return string
     */
    protected function getAbsoluteFile($environment)
    {
        return $this->getFolder() . $environment . '.yml';
    }

    /**
     * @return string
     */
    protected function getFolder() {
        return getcwd() . self::ENVIRONMENT_PATH;
    }
}