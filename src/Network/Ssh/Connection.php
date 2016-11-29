<?php
namespace Nolotz\Network\Ssh;

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
class Connection
{
    /**
     * @var SFTP|SSH2
     */
    protected $client = null;

    /**
     * @var Remote
     */
    protected $remote = null;


    public function connect()
    {

    }

    public function disconnect()
    {
        $this->client->disconnect();
    }

    protected function bootstrap()
    {

    }

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
     * @param $path
     *
     * @return bool
     */
    public function delete($path)
    {
        return $this->client->delete($path);
    }

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