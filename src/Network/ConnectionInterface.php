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

namespace Nolotz\Facilior\Network;


interface ConnectionInterface
{
    /**
     * connect
     *
     * @return $this
     */
    public function connect();

    /**
     * Disconnect
     *
     * @return void
     */
    public function disconnect();

    /**
     * delete
     *
     * @param $path
     *
     * @return bool
     */
    public function delete($path);

    /**
     * rename
     *
     * @param $remote
     * @param $newRemote
     *
     * @return bool
     */
    public function rename($remote, $newRemote);

    /**
     * @param $remote
     *
     * @return bool
     */
    public function exists($remote);

    /**
     * Download the contents of a remote file.
     *
     * @param string $remote
     * @param string $local
     *
     * @return void
     */
    public function get($remote, $local);

    /**
     * Upload a local file to the server.
     *
     * @param string $local
     * @param string $remote
     *
     * @return void
     */
    public function put($local, $remote);

    /**
     * Upload a string to to the given file on the server.
     *
     * @param string $remote
     * @param string $contents
     *
     * @return void
     */
    public function putString($remote, $contents);

    /**
     * Get the contents of a remote file.
     *
     * @param string $remote
     *
     * @return string
     */
    public function getString($remote);
}