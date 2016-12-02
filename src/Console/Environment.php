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
    protected $database;

    /**
     * @var object
     */
    protected $files;

    /**
     * Environment constructor.
     */
    protected function __construct($database, $files)
    {
        $this->database = $database;
        $this->files = $files;
    }

    /**
     * @return object
     */
    public function getDatabase()
    {
        return $this->database;
    }

    /**
     * @param object $database
     */
    public function setDatabase($database)
    {
        $this->database = $database;
    }

    /**
     * @return object
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param object $files
     */
    public function setFiles($files)
    {
        $this->files = $files;
    }

    /**
     * @return static
     */
    public static function create($rawEnvironment) {
        $database = [];
        $files = [];

        if(!empty($rawEnvironment['database']))
            $database = (object) $rawEnvironment['database'];

        if(!empty($rawEnvironment['files']))
            $files = (object) $rawEnvironment['files'];

        return new static($database, $files);
    }
}