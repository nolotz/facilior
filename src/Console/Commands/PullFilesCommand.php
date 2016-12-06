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


use Nolotz\Facilior\Console\Command;
use Nolotz\Facilior\Console\EnvironmentFactory;
use Nolotz\Facilior\Files\ExportService;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PullFilesCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'pull:files {remote} {destination=local} {--rsync}';

    /**
     * @var string
     */
    protected $description = 'Downloads all required project resources from remote host';

	/**
	 * handle
	 *
	 * @param \Symfony\Component\Console\Input\InputInterface   $input
	 * @param \Symfony\Component\Console\Output\OutputInterface $output
	 *
	 * @return int
	 */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $remote = EnvironmentFactory::make($this->argument('remote'));
        $destination = EnvironmentFactory::make($this->argument('destination'));

        $exportService = new ExportService($this->input, $this->output);

        if($this->option('rsync')) {
        	$exportService->replaceCommand('scp', 'rsync');
		}

        $result = $exportService->setEnvironments($remote, $destination)
			->run();

        if($result) {
			$this->info('Success!! Transfer successfully completed.');
			return 0;
		}

		$this->error('Failed!! Please check your logs.');
		return -1;
    }
}
