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
        if(is_dir('.facilior')){
            $this->consoleOutput->output('<fg=red>Error!!</> Already exists .facilior directory.');
            return false;
        }

        if(!file_exists('.facilior')){
            $this->createFolderStructure();
        }


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
     * Creates all necessary folders.
     */
    protected function createFolderStructure()
    {
        mkdir('.facilior');
        mkdir('.facilior/logs');
        mkdir('.facilior/environments');
        touch('.facilior/general.yml');
    }
}