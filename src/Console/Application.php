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

namespace Nolotz\Facilior\Console;

use Symfony\Component\Process\ProcessUtils;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Illuminate\Contracts\Console\Application as ApplicationContract;

class Application extends SymfonyApplication implements ApplicationContract
{
    /**
     * The output from the previous command.
     *
     * @var \Symfony\Component\Console\Output\BufferedOutput
     */
    protected $lastOutput;

    /**
     * Application constructor.
     *
     * @param string $version
     */
    public function __construct($version)
    {
        parent::__construct('Facilior', $version);

        $this->setAutoExit(false);
        $this->setCatchExceptions(false);
    }

    /**
     * Run an Artisan console command by name.
     *
     * @param  string $command
     * @param  array  $parameters
     *
     * @return int
     */
    public function call($command, array $parameters = [])
    {
        $parameters = collect($parameters)->prepend($command);
        $this->lastOutput = new BufferedOutput;
        $this->setCatchExceptions(false);
        $result = $this->run(new ArrayInput($parameters->toArray()), $this->lastOutput);
        $this->setCatchExceptions(true);

        return $result;
    }

    /**
     * Get the output for the last run command.
     *
     * @return string
     */
    public function output()
    {
        return $this->lastOutput ? $this->lastOutput->fetch() : '';
    }

    /**
     * resolveCommands
     * @param array $commands
     *
     * @return $this
     */
    public function resolveCommands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        foreach($commands as $command) {
            $this->add(new $command);
        }

        return $this;
    }

    /**
     * add
     *
     * @param \Symfony\Component\Console\Command\Command $command
     *
     * @return null|\Symfony\Component\Console\Command\Command
     */
    public function add(SymfonyCommand $command)
    {
        return parent::add($command);
    }
}
