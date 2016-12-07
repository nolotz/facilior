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

namespace Nolotz\Facilior\Console\Commands;


use Nolotz\Facilior\Database\ExportService;
use Nolotz\Facilior\Console\Command;
use Nolotz\Facilior\Console\EnvironmentFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullDatabaseCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'pull:database {remote} {destination=local}';

    /**
     * @var string
     */
    protected $description = 'Dumps the remote database and import it to a specific destination.';

	/**
	 * @return int
	 */
    public function handle()
    {
		$remote = EnvironmentFactory::make($this->argument('remote'));
		$destination = EnvironmentFactory::make($this->argument('destination'));

		$exportService = new ExportService($this->input, $this->output);
		$result = $exportService->setEnvironments($remote, $destination)
			->run();

		return $result ? 0 : -1;
    }
}
