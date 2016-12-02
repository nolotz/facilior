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
use Nolotz\Facilior\Foundation\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ExportService
{
    /**
     * @var Environment
     */
    protected $sourceEnvironment;

    /**
     * @var Environment
     */
    protected $destinationEnvironment;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * ExportService constructor.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @throws \Exception
     */
    public function direct()
    {
        throw new \Exception('Todo: simple cp');
    }

    /**
     * @param \Nolotz\Facilior\Console\Environment $source
     * @param \Nolotz\Facilior\Console\Environment $destination
     */
    public function tunnel(Environment $source, Environment $destination)
    {
        $this->sourceEnvironment = $source;
        $this->destinationEnvironment = $destination;

        foreach($source->getFiles() as $key => $file) {
            // Source: Remote & Destination: Local
            if(is_array($source->getSsh()) && !is_array($destination->getSsh())) {
                $this->pullEntryFromRemoteToLocal($key);
            }

            // Source: Remote & Destination: Remote
            if(is_array($source->getSsh()) && is_array($destination->getSsh())) {

            }

            // Source: Local & Destination: Remote
            if(!is_array($source->getSsh()) && is_array($destination->getSsh())) {
                $this->pullEntryFromLocalToRemote($key);
            }

        }

    }

    /**
     * @param $key
     */
    protected function pullEntryFromRemoteToLocal($key)
    {
        $commands = Command::get('get-files-from-extern-to-local');
        foreach($commands as $command) {
            $command = $this->parseVariablesToString($command, [
                '$DESTINATION_DIR' => $this->destinationEnvironment->getFiles()[$key],
                '$SOURCE_DIR' => $this->sourceEnvironment->getFiles()[$key],
                '$SOURCE_USER' => $this->sourceEnvironment->getSsh()['username'],
                '$SOURCE_HOST' => $this->sourceEnvironment->getSsh()['host'],
            ]);

            $this->output->writeln('Running: ' . $command);
            $process = new Process($command);
            $process->setTimeout(0);
            $process->run();

            $process->wait(function ($type, $buffer) {
                $this->output->write($buffer);
            });

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->output->writeln('Completed.');
            $this->output->writeln('');
        }
    }

    /**
     * @param       $string
     * @param array $variables
     *
     * @return mixed
     */
    protected function parseVariablesToString($string, array $variables = array())
    {
        foreach($variables as $key => $variable) {
            $string = str_replace($key, $variable, $string);
        }

        return $string;
    }

    private function pullEntryFromLocalToRemote($key)
    {
        $commands = Command::get('get-files-from-local-to-extern');
        foreach($commands as $command) {
            $command = $this->parseVariablesToString($command, [
                '$DESTINATION_DIR' => $this->destinationEnvironment->getFiles()[$key],
                '$DESTINATION_USER' => $this->destinationEnvironment->getSsh()['username'],
                '$DESTINATION_HOST' => $this->destinationEnvironment->getSsh()['host'],
                '$SOURCE_DIR' => $this->sourceEnvironment->getFiles()[$key],
            ]);

            $this->output->writeln('Running: ' . $command);
            $process = new Process($command);
            $process->setTimeout(0);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $this->output->writeln('Completed.');
            $this->output->writeln('');
        }
    }
}