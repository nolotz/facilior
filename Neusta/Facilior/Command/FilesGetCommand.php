<?php
namespace Neusta\Facilior\Command;

use Symfony\Component\Console\Input\InputArgument;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 25.02.2016
 * Time: 13:04
 */



class FilesGetCommand extends AbstractCommand
{
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

    protected function execute()
    {

    }
}
