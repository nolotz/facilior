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

namespace Nolotz\Facilior\Network\Ssh;


use Nolotz\Facilior\Console\Environment;
use phpseclib\Crypt\RSA;
use phpseclib\System\SSH\Agent;

class Remote
{
    /**
     * @var string
     */
    protected $host = '';

    /**
     * @var array
     */
    protected $auth = [];

    /**
     * @var int
     */
    protected $port = 22;

    /**
     * @var int
     */
    protected $timeout = 10;

    /**
     * Remote constructor.
     *
     * @param $host
     * @param $auth
     * @param $port
     * @param $timeout
     */
    public function __construct($host, $port, $timeout)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
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
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * creates a new remote host
     *
     * @param       $host
     * @param int   $port
     * @param int   $timeout
     *
     * @return static
     */
    public static function create($host, $port = 22, $timeout = 10)
    {
        return new static($host, $port, $timeout);
    }
}