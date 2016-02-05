<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 05.02.2016
 * Time: 09:52
 */

namespace Neusta\Facilior;


use Neusta\Facilior\Console\ConsoleOutputInterface;

class Database
{

    /**
     * @var bool
     */
    protected $lastCommandFailed = false;

    /**
     * @var Environment|null
     */
    protected $environment = null;

    /**
     * @var ConsoleOutputInterface|null
     */
    protected $consoleOutput = null;

    /**
     * Database constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->consoleOutput = new ConsoleOutputInterface();
    }

    /**
     * Creates Temp File
     * @return string
     */
    protected function createTempFile()
    {
        return tempnam(sys_get_temp_dir(), 'facilior');
    }

    /**
     * @param $destinationFile
     * @return string
     */
    protected function tunneledDatabaseExport($destinationFile)
    {
        $command = 'ssh -l ' . escapeshellarg($this->environment->getSshUsername()) . ' ' . escapeshellarg($this->environment->getSshHost());
        $command .= ' "mysqldump --add-drop-table -u ' . escapeshellarg($this->environment->getUsername()) . ' --password=\'' . escapeshellarg($this->environment->getPassword()) . '\'';
        $command .= ' ' . escapeshellarg($this->environment->getDatabase()) . '" > ' . $destinationFile;

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));

        return $returnVar;
    }

    protected function databaseExport($destinationFile)
    {

    }

    public function exportSql()
    {
        $pathFile = $this->createTempFile();
        $status = $this->tunneledDatabaseExport($pathFile);

        if($status != 0){
            $this->lastCommandFailed = true;
        }

        return $pathFile;
    }

    public function importSql($pathFile)
    {
        
    }

    /**
     * @return boolean
     */
    public function isLastCommandFailed()
    {
        return $this->lastCommandFailed;
    }
}