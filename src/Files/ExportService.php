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

        foreach ($this->source->getFiles() as $key => $file) {
            foreach ($commands as $command) {
                $this->processes[] = $this->createProcess(
                	$command,
					[
						'currentFileKey' => $key
					]
				);
            }
        }

        return $this->evaluateResult();
    }

    /**
     * @return array
     */
    protected function commandKeys()
    {
        return [
            'files.extern-local' => is_array($this->source->getSsh()) && !is_array($this->destination->getSsh()),
            'files.extern-extern' => is_array($this->source->getSsh()) && is_array($this->destination->getSsh()),
            'files.local-extern' => !is_array($this->source->getSsh()) && is_array($this->destination->getSsh()),
        ];
    }
}
