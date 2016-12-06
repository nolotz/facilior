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
	 * @param                                                   $command
	 * @param array                                             $variables
	 * @param \Symfony\Component\Console\Output\OutputInterface $outputInterface
	 *
	 * @return \Symfony\Component\Process\Process
	 */
    public static function create($command, array $variables, array $replacedCommands = array(), OutputInterface $outputInterface) {
        $parsedCommand = (new CommandParser())->parse($command, $variables);
        $parsedCommand = str_replace(array_keys($replacedCommands), $replacedCommands, $parsedCommand);

        logger('Executing command: ' . $parsedCommand);
        $outputInterface->writeln('Executing command: ' . $parsedCommand);

        /** @var Process $process */
        $process = new Process($parsedCommand);

        $process->setTimeout(0)
            ->run();

        if(!$process->isSuccessful()) {
            logger($process->getOutput());
            logger('Executing command wasn\'t successful: ' . $process->getErrorOutput());
			$outputInterface->writeln('Executing command wasn\'t successful. Please check your logs.');
            throw new ProcessFailedException($process);
        }

        logger('Executing command was successful');
		$outputInterface->writeln('Successful executed.');
        return $process;
    }
}
