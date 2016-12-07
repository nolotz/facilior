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

namespace Nolotz\Facilior\Foundation\Process;


use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ProcessFactory
{
	/**
	 * @param array $command
	 * @param array $variables
	 * @param array $replacedCommands
	 *
	 * @return \Symfony\Component\Process\Process
	 */
    public static function create(array $command, array $variables, array $replacedCommands = array()) {
        $parsedCommand = (new CommandParser())->parse($command, $variables);
		$parsedCommand['command'] = str_replace(array_keys($replacedCommands), $replacedCommands, $parsedCommand['command']);

        logger('Executing command: ' . $parsedCommand['command']);
        output()->comment($parsedCommand['description']);

        /** @var Process $process */
        $process = new Process($parsedCommand['command']);

        $process->setTimeout(0)
            ->run();

		logger($process->getOutput());
		logger($process->getErrorOutput());

        if(!$process->isSuccessful()) {
            logger('Executing command wasn\'t successful: ' . $process->getErrorOutput());
			output()->warn('Executing command wasn\'t successful. Please check your logs');
            throw new ProcessFailedException($process);
        }

        logger('Executing command was successful');
		output()->info('Successfully executed');
		output()->line('');

        return $process;
    }
}
