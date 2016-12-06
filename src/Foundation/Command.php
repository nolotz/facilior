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

namespace Nolotz\Facilior\Foundation;


class Command
{
    private static $instance = null;

    /**
     * @var array
     */
    private $commands = [];

    /**
     * Command constructor.
     */
    private function __construct()
    {
        $this->commands = json_decode(file_get_contents(FACILIOR_BIN . '/../commands.json'), true);
    }

    /**
     * getKey
     *
     * @param $key
     *
     * @return mixed
     */
    public function getKey($key) {
        return array_get($this->commands, $key, false);
    }

    /**
     * Wrapper class to access commands without instance class
     * @param $key
     *
     * @return mixed
     */
    public static function get($key) {
        if(!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance->getKey($key);
    }
}