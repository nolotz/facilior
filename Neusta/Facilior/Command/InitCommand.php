<?php
namespace Neusta\Facilior\Command;
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
     * @param InputInterface $input
     * @throws ProjectAlreadyExistsException
     * @return bool
     */
    protected function execute(InputInterface $input)
    {
        $exitCode = 50;
        $configDir = getcwd() . '/.facilior';

        if(file_exists($configDir)){
            $this->consoleOutput->output('<fg=red>Error!!</> Already exists .facilior directory.', 1, 2);
            return false;
        }

        $result = $this->createFolderStructure();

        if($result){
            $this->consoleOutput->output('<fg=green>Success!!</> The configuration has been generated at <fg=cyan>.facilior</> directory.', 1);
            $this->consoleOutput->output('<fg=default>Please!!</> Adjust the enviroments to ', 1, 2);
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
            ->setDescription('Creates a new Project.')
        ;
    }

    /**
     * @return bool
     */
    protected function createFolderStructure()
    {
        $result = [];
        $result[] = mkdir('.facilior');
        $result[] = mkdir('.facilior/logs');
        $result[] = mkdir('.facilior/environments');
        $result[] = touch('.facilior/general.yml');

        $result[] = touch('.facilior/environments/live.yml');
        $result[] = touch('.facilior/environments/development.yml');
        $result[] = touch('.facilior/environments/intern.yml');
        $result[] = touch('.facilior/environments/staging.yml');

        return !in_array(false, $result);
    }
}