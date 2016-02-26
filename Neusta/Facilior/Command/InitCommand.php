<?php
namespace Neusta\Facilior\Command;

use Neusta\Facilior\Environment;
use Neusta\Facilior\Init\ProjectAlreadyExistsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:49
 */
class InitCommand extends AbstractCommand
{

    /**
     * @throws ProjectAlreadyExistsException
     * @return bool
     */
    protected function execute()
    {
        $exitCode = 50;
        $configDir = getcwd() . '/.facilior';

        if (file_exists($configDir)) {
            $this->consoleOutput->output('<fg=red>Error!!</> Already exists .facilior directory.', 1, 2);
            return false;
        }

        $result = $this->createFolderStructure();

        if ($result) {
            $this->consoleOutput->output(
                '<fg=green>Success!!</> The configuration has been generated at <fg=cyan>.facilior</> directory.',
                1
            );

            $this->consoleOutput->output(
                '<fg=default>Please!!</> Dont forget to configure your enviroments '
                . 'under<fg=magenta>.facilior/enviroments</>.',
                1,
                2
            );

        } else {
            $this->consoleOutput->output('<fg=red>Error!!</> Unable to generate the configuration', 1, 2);
            $exitCode = 0;
        }

        return $exitCode;
    }

    /**
     * sets settings for command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('init')
            ->setDescription('Creates a new Project.');
    }

    /**
     * @return bool
     */
    protected function createFolderStructure()
    {
        $result = [];
        $result[] = mkdir(getcwd() . '/.facilior');
        $result[] = mkdir(getcwd() . '/.facilior/logs');
        $result[] = mkdir(getcwd() . '/.facilior/environments');

        $result[] = $this->createGeneralConfig();
        $result[] = $this->createEnvironments();
        $result[] = $this->createGitIgnoreInLogFolder();

        return !in_array(false, $result);
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function createEnvironments()
    {
        $result = [];
        $result[] = Environment::create('live');
        $result[] = Environment::create('local');
        $result[] = Environment::create('development');
        $result[] = Environment::create('staging');

        return $result;
    }

    /**
     * creates the general.yml file
     * @return int|false
     */
    protected function createGeneralConfig()
    {
        return file_put_contents(
            getcwd() . '/.facilior/general.yml',
            '#general config' . PHP_EOL . 'local_environment: local'
        );
    }

    /**
     * Creates the .gitignore in logs folder
     * @return int|false
     */
    protected function createGitIgnoreInLogFolder()
    {
        return file_put_contents(getcwd() . '/.facilior/logs/.gitignore', '*' . PHP_EOL . '!.gitignore');
    }

}