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

namespace Nolotz\Facilior\Foundation;


use Nolotz\Facilior\Console\Environment;
use Nolotz\Facilior\Foundation\Process\ProcessFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class AbstractExportService
{
    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @var InputInterface
     */
    protected $inputInterface;

	/**
	 * @var Environment
	 */
    protected $source = null;

	/**
	 * @var Environment
	 */
    protected $destination = null;

    /**
     * @var OutputInterface
     */
    protected $outputInterface;

	/**
	 * @var Process[]
	 */
    protected $processes = [];

	/**
	 * @var array
	 */
	protected $replacedCommands = [];

	/**
     * ExportService constructor.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     */
    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->inputInterface = $input;
        $this->outputInterface = $output;
    }

    /**
     * assignVariables
     *
     * @param $variables
     *
     * @throws \Exception
     */
    public function assignVariables(array $variables)
    {
        foreach ($variables as $key => $variable) {
            if($variable instanceof Environment) {
                $this->variables[$key] = $variable->toArray();
            }

            if(is_array($variable)) {
                $this->variables[$key] = $variable;
            }
        }
    }

    /**
     * @param array $additionalVariables
     *
     * @return array
     */
    protected function getVariables(array $additionalVariables = [])
    {
        return array_merge($this->variables, $additionalVariables);
    }

	/**
	 * commandKeys
	 *
	 * @return array
	 */
    protected abstract function commandKeys();

	/**
	 * @return int|string
	 * @throws \Exception
	 */
    protected function findCommandKey()
    {
        $commandKeys = $this->commandKeys();
        foreach ($commandKeys as $key => $result) {
        	if ($result) return $key;
		}

		throw new \Exception('Invalid command key');
    }

	/**
	 * setEnvironments
	 *
	 * @param Environment $source
	 * @param Environment $destination
	 *
	 * @return $this
	 */
	public function setEnvironments($source, $destination)
	{
		$this->source = $source;
		$this->destination = $destination;

		return $this;
    }

	/**
	 * evaluateResult
	 *
	 * @return bool
	 */
	protected function evaluateResult()
	{
		$results = [];
		foreach ($this->processes as $process) {
			$results[] = $process->isSuccessful();
		}

		return !in_array(false, $results);
    }

	/**
	 * @param array $command
	 * @param array $additionalVariables
	 *
	 * @return \Symfony\Component\Process\Process
	 */
	protected function createProcess($command, $additionalVariables = [])
	{
		return ProcessFactory::create($command, $this->getVariables($additionalVariables), $this->replacedCommands);
    }

	/**
	 * @param $search
	 * @param $replace
	 */
	public function replaceCommand($search, $replace)
	{
		$this->replacedCommands[$search] = $replace;
    }
}
