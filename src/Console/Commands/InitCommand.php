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

namespace Nolotz\Facilior\Console\Commands;


use Nolotz\Facilior\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'init';

    /**
     * @var string
     */
    protected $description = 'Create a new facilior project in this folder.';

    /**
     * @var array
     */
    protected $directories = [
        'environments',
        'logs',
    ];

    /**
     * @var array
     */
    protected $files = [
        'environments/live.yml.stub',
        'environments/local.yml.stub',
        'environments/staging.yml.stub',
        'logs/.gitignore.stub',
    ];

    /**
     * This is the stub dir relative to the bin dir
     *
     * @var string
     */
    protected $stubDir = '/../stubs/';

    /**
     * handle
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Exception
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $this->checkFaciliorIsAlreadyInstalled();

        if (!mkdir(getcwd() . '/.facilior')) {
            throw new \Exception('Cant create .facilior folder.');
        }

        $this->createDirectories();
        $this->createStubFiles();

        $this->info('Success! Facilior was successfully initialized.');
    }

    /**
     * Creates required folders
     *
     * @throws \Exception
     * @return void
     */
    protected function createDirectories()
    {
        foreach ($this->directories as $directory) {
            if (!mkdir(getcwd() . '/.facilior/' . $directory)) {
                throw new \Exception('Cant create folder ' . $directory);
            }
        }
    }

    /**
     * Check if facilior is already installed in this folder
     *
     * @return void
     */
    protected function checkFaciliorIsAlreadyInstalled()
    {
        $possibleFaciliorPath = getcwd() . '/.facilior';

        if (file_exists($possibleFaciliorPath) && is_dir($possibleFaciliorPath)) {
            $this->error('A .facilior folder already exists in this folder.');
            exit(-1);
        }
    }

    /**
     * Creates all stub files
     *
     * @return void
     */
    protected function createStubFiles()
    {
        $stubDir = FACILIOR_BIN . $this->stubDir;
        $faciliorDir = getcwd();

        foreach ($this->files as $file) {
            $fileContent = file_get_contents($stubDir . $file);
            $filePath = $faciliorDir . '/.facilior/' . str_replace('.stub', '', $file);
            file_put_contents($filePath, $fileContent);
        }
    }
}