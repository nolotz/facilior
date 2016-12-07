<?php
namespace Nolotz\Facilior\Console;

use Nolotz\Facilior\Console\Commands\InitCommand;
use Nolotz\Facilior\Console\Commands\PullDatabaseCommand;
use Nolotz\Facilior\Console\Commands\PullFilesCommand;
use Nolotz\Facilior\Console\Commands\TestCommand;
use Nolotz\Facilior\Foundation\LoggerFactory;
use Nolotz\Facilior\Foundation\Test;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Console\Application as ConsoleApplication;

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
class Kernel
{
    /**
     * @var string
     */
    const VERSION = '1.0';

    /**
     * @var Application
     */
    protected $application = null;

    /**
     * @var array
     */
    protected $commands = [
        PullDatabaseCommand::class,
        InitCommand::class,
        PullFilesCommand::class,
    ];

    /**
     * @param \Symfony\Component\Console\Input\ArgvInput      $argvInput
     * @param \Symfony\Component\Console\Output\ConsoleOutput $consoleOutput
     *
     * @return int|null
     */
    public function handle(ArgvInput $argvInput, ConsoleOutput $consoleOutput)
    {
		$result = -1;

        try {
			output()->init(new OutputStyle($argvInput, $consoleOutput));
			$this->displayLogo();
			hook()->fire('on-start');
			$result = $this->getApplication()->run($argvInput, $consoleOutput);
        } catch (\Exception $e) {
			hook()->fire('on-error');
            $this->renderException($consoleOutput, $e);
        } catch (\Throwable $e) {
			hook()->fire('on-error');
            $e = new FatalThrowableError($e);
            $this->renderException($consoleOutput, $e);
        }

        if ($result == 0) {
			output()->info('Success!! Everything got successfully executed.');
		} else {
        	output()->warn('Failed!! Please check your logs');
		}

        return $result;
    }

    /**
     * @return Application
     */
    protected function getApplication()
    {
        if(is_null($this->application)) {
            return $this->application = (new Application(static::VERSION))
                ->resolveCommands($this->commands);
        }

        return $this->application;
    }

    /**
     * @param            $output
     * @param \Exception $e
     */
    protected function renderException($output, \Exception $e)
    {
        (new ConsoleApplication())->renderException($e, $output);
    }

	/**
	 * @return void
	 */
	protected function displayLogo()
	{
		output()->line('  ______         _ _ _            ');
		output()->line(' |  ____|       (_) (_)           ');
		output()->line(' | |__ __ _  ___ _| |_  ___  _ __ ');
		output()->line(" |  __/ _` |/ __| | | |/ _ \| \'__|");
		output()->line(' | | | (_| | (__| | | | (_) | |   ');
		output()->line(' |_|  \__,_|\___|_|_|_|\___/|_|   ');
		output()->line('                                  ');
    }

}
