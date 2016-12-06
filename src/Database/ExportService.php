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

namespace Nolotz\Facilior\Database;


use Nolotz\Database\ExportInterface;
use Nolotz\Database\ExportResult;
use Nolotz\Facilior\Foundation\AbstractExportService;
use Nolotz\Facilior\Foundation\Command;

class ExportService extends AbstractExportService
{
	/**
	 * run
	 *
	 * @return bool
	 */
	public function run()
	{
		$this->assignVariables(['source' => $this->source, 'destination' => $this->destination]);
		$commands = Command::get($this->findCommandKey());

		foreach ($commands as $command) {
			$this->processes[] = $this->createProcess($command);
		}

		return $this->evaluateResult();
	}

	/**
	 * @return array
	 */
	protected function commandKeys()
	{
		return [
			'database.extern-local' => is_array($this->source->getSsh()) && !is_array($this->destination->getSsh()),
			'database.extern-extern' => is_array($this->source->getSsh()) && is_array($this->destination->getSsh()),
			'database.local-extern' => !is_array($this->source->getSsh()) && is_array($this->destination->getSsh()),
		];
	}
}
