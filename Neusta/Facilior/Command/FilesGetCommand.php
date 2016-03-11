<?php
namespace Neusta\Facilior\Command;

use Neusta\Facilior\Environment;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 25.02.2016
 * Time: 13:04
 */



class FilesGetCommand extends AbstractCommand
{

    /**
     * @var null|InputInterface
     */
    protected $inputInterface = null;

    /**
     * sets settings for command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('files:get')
            ->setDescription('Downloads Files and Folders which specified in general.yml')
            ->addArgument('from', InputArgument::REQUIRED, 'Which Source should be used?');
    }

    protected function execute(InputInterface $input)
    {
        $this->inputInterface = $input;

        $destArgument = $input->getArgument('from');
        $destEnvironment = Environment::get($destArgument);
        print_r($destEnvironment->getFolders());
        print_r($destEnvironment->getFiles());
    }
}
