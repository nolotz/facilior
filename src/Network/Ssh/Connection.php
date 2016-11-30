<?php
namespace Nolotz\Facilior\Network\Ssh;

use Nolotz\Facilior\Network\ConnectionInterface;
use phpseclib\Net\SFTP;
use phpseclib\Net\SSH2;

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
class Connection implements ConnectionInterface
{
    /**
     * @var SFTP|SSH2
     */
    protected $client = null;

    /**
     * @var Remote
     */
    protected $remote = null;

    /**
     * Connection constructor.
     *
     * @param \Nolotz\Facilior\Network\Ssh\Remote $remote
     */
    public function __construct(Remote $remote)
    {
        $this->remote = $remote;
    }

    /**
     * connect
     *
     * @return $this
     */
    public function connect()
    {
        if ($this->client instanceof SSH2) {
            $this->disconnect();
        }

        $this->client = new SFTP(
            $this->remote->getHost(),
            $this->remote->getPort(),
            $this->remote->getTimeout()
        );

        return $this;
    }

    /**
     * Disconnect
     *
     * @return void
     */
    public function disconnect()
    {
        $this->client->disconnect();
    }

    /**
     * status
     *
     * @return false|int
     */
    public function status()
    {
        return $this->client->getExitStatus();
    }

    /**
     * @return string|null
     */
    public function nextLine()
    {
        $value = $this->client->_get_channel_packet(SSH2::CHANNEL_EXEC);

        return $value === true ? null : $value;
    }

    /**
     * delete
     *
     * @param $path
     *
     * @return bool
     */
    public function delete($path)
    {
        return $this->client->delete($path);
    }

    /**
     * rename
     *
     * @param $remote
     * @param $newRemote
     *
     * @return bool
     */
    public function rename($remote, $newRemote)
    {
        return $this->client->rename($remote, $newRemote);
    }

    /**
     * @param $remote
     *
     * @return bool
     */
    public function exists($remote)
    {
        return $this->client->file_exists($remote);
    }

    /**
     * Download the contents of a remote file.
     *
     * @param string $remote
     * @param string $local
     *
     * @return void
     */
    public function get($remote, $local)
    {
        $this->client->get($remote, $local);
    }

    /**
     * Get the contents of a remote file.
     *
     * @param string $remote
     *
     * @return string
     */
    public function getString($remote)
    {
        return $this->client->get($remote);
    }

    /**
     * Upload a local file to the server.
     *
     * @param string $local
     * @param string $remote
     *
     * @return void
     */
    public function put($local, $remote)
    {
        $this->client->put($remote, $local, SFTP::SOURCE_LOCAL_FILE);
    }

    /**
     * Upload a string to to the given file on the server.
     *
     * @param string $remote
     * @param string $contents
     *
     * @return void
     */
    public function putString($remote, $contents)
    {
        $this->client->put($remote, $contents);
    }

    /**
     * @param $command
     *
     * @return string
     */
    public function exec($command)
    {
        return $this->client->exec($command, false);
    }
}