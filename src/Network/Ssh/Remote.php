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

namespace Nolotz\Network\Ssh;


use Crypt_RSA;
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
    public function __construct($host, $auth, $port, $timeout)
    {
        $this->host = $host;
        $this->auth = $auth;
        $this->port = $port;
        $this->timeout = $timeout;
    }

    public function hasPassword()
    {
        return !empty($this->auth['password']) && $this->auth[''];
    }

    /**
     * creates a new remote host
     *
     * @param       $host
     * @param array $auth
     * @param int   $port
     * @param int   $timeout
     *
     * @return static
     */
    public static function create($host, array $auth, $port = 22, $timeout = 10)
    {
        return new static($host, $auth, $port, $timeout);
    }
}