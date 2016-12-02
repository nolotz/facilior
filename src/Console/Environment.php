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


class Environment
{
    /**
     * @var object
     */
    protected $database = [
        'username' => '',
        'password' => '',
        'host'      => '',
        'database' =>  ''
    ];

    /**
     * @var object
     */
    protected $files = [];

    /**
     * @var array
     */
    protected $ssh = [
        'username' => '',
        'password' => '',
        'host'      => '',
        'timeout'  => 10,
        'port'     =>  22
    ];

    /**
     * Environment constructor.
     */
    protected function __construct(array $database, array $files, $ssh = false)
    {
        $this->database = $database;
        $this->files = $files;
        $this->ssh = $ssh;
    }

    /**
     * @return object
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @return object
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return array
     */
    public function getSsh()
    {
        return $this->ssh;
    }

    /**
     * @return static
     */
    public static function create($rawEnvironment) {
        return new static($rawEnvironment['database'], $rawEnvironment['files'], $rawEnvironment['ssh']);
    }
}