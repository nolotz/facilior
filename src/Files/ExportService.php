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

namespace Nolotz\Facilior\Files;


use Nolotz\Facilior\Console\Environment;
use Nolotz\Facilior\Network\ConnectionInterface;
use Nolotz\Facilior\Network\Ssh\Connection;
use Nolotz\Facilior\Network\Ssh\Remote;

class ExportService
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @throws \Exception
     */
    public function directExport()
    {
        throw new \Exception('Todo: simple cp');
    }

    /**
     * @param \Nolotz\Facilior\Console\Environment $source
     * @param \Nolotz\Facilior\Console\Environment $destination
     */
    public function tunneledExport(Environment $source, Environment $destination)
    {
        $sourceFiles = $source->getFiles();
        $destinationFiles = $destination->getFiles();

        $this->connection = new Connection(
            Remote::create(
                $sourceFiles->host,
                isset($sourceFiles->port) ? $sourceFiles->port : 22,
                isset($sourceFiles->timeout) ? $sourceFiles->timeout : 10
            )
        );

        foreach($sourceFiles['paths'] as $key => $path) {

        }
    }
}