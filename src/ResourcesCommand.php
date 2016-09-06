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

namespace Lotzer\Facilior;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResourcesCommand extends SymfonyCommand
{
    /**
     * The input interface.
     *
     * @var InputInterface
     */
    public $input;

    /**
     * The output interface.
     *
     * @var OutputInterface
     */
    public $output;

    /**
     * The path to the local project.
     *
     * @var string
     */
    public $path;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('resources')
            ->setDescription('Synchronize project resources from specific server')
            ->addArgument('origin', InputArgument::REQUIRED, 'The origin server from where the files should be synced.')
            ->addArgument('destination', InputOption::VALUE_OPTIONAL, 'The destination system')
            ->addUsage('development live');
    }

    /**
     * Execute the command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = new SymfonyStyle($input, $output);

        $this->path = getcwd();

        
    }
}