<?php
namespace Neusta\Facilior;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 05.02.2016
 * Time: 09:52
 */



use Neusta\Facilior\Console\ConsoleService;

class Database
{

    /**
     * @var bool
     */
    protected $lastCommandFailed = false;

    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var Environment|null
     */
    protected $environment = null;

    /**
     * @var ConsoleService|null
     */
    protected $consoleOutput = null;

    /**
     * Database constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->consoleOutput = new ConsoleService();
        $this->fileService = new FileService();
    }

    /**
     * @param $destinationFile
     * @return string
     */
    protected function tunneledDatabaseExport($destinationFile)
    {
        $command = 'ssh -l ' . escapeshellarg($this->environment->getSshUsername()) . ' ' .
            escapeshellarg($this->environment->getSshHost());
        $command .= ' "mysqldump --add-drop-table -u ' .
            escapeshellarg($this->environment->getUsername()) . ' --password=\'' .
            escapeshellarg($this->environment->getPassword()) . '\'';

        $command .= ' ' . escapeshellarg($this->environment->getDatabase()) . ' | gzip -3 -c" > ' .
            $destinationFile . '.gz';

        $command .= ' && gunzip ' . $destinationFile . '.gz';

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));

        return $returnVar;
    }

    /**
     * @param $destinationFile
     * @return mixed
     */
    protected function databaseExport($destinationFile)
    {
        $command = 'mysqldump --add-drop-table -h ' . escapeshellarg($this->environment->getHost()) . ' -u ' .
            escapeshellarg($this->environment->getUsername()) . ' --password=\'' .
            escapeshellarg($this->environment->getPassword()) . '\'';

        $command .= ' ' . escapeshellarg($this->environment->getDatabase()) . ' > ' . $destinationFile;

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));

        return $returnVar;
    }

    /**
     * @param $sourceFile
     * @return string
     */
    protected function tunneledDatabaseImport($sourceFile)
    {
        //Uploading SQL Dump to Remote Host
        $dumpName = uniqid(time() . '_facilior_');
        exec("gzip -3 " . $sourceFile, $output, $returnVar);

        if ($returnVar != 0) {
            return $returnVar;
        }

        $command = "scp " . escapeshellarg($sourceFile) . '.gz ' .
            escapeshellarg($this->environment->getSshUsername()) . '@' .
            escapeshellarg($this->environment->getSshHost()) . ':~/' . $dumpName . '.gz';

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));

        if ($returnVar != 0) {
            return $returnVar;
        }


        $command = 'ssh -l ' . escapeshellarg($this->environment->getSshUsername()) . ' ' .
            escapeshellarg($this->environment->getSshHost()) . ' "gunzip ' . $dumpName . '.gz' . ' && ';

        $command .= 'mysql -h ' . escapeshellarg($this->environment->getHost()) . ' -u ' .
            escapeshellarg($this->environment->getUsername()) . ' -p' .
            escapeshellarg($this->environment->getPassword());

        $command .= ' ' . escapeshellarg($this->environment->getDatabase()) . ' < ' .
            $dumpName . ' && rm ' . $dumpName . '"';

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));
        return $returnVar;
    }

    /**
     * @param $sourceFile
     * @return mixed
     */
    protected function databaseImport($sourceFile)
    {
        $command = 'mysql -h ' . escapeshellarg($this->environment->getHost()) . ' -u ' .
            escapeshellarg($this->environment->getUsername()) . ' -p' .
            escapeshellarg($this->environment->getPassword());
        $command .= ' ' . escapeshellarg($this->environment->getDatabase()) .
            ' < ' . $sourceFile . ' && rm ' . $sourceFile;

        exec($command, $output, $returnVar);
        $this->consoleOutput->log(implode(PHP_EOL, $output));
        return $returnVar;
    }


    /**
     * @return string
     */
    public function exportSql()
    {
        $pathFile = $this->fileService->createTempFile();

        if ($this->environment->isSshTunnel()) {
            $status = $this->tunneledDatabaseExport($pathFile);
        } else {
            $status = $this->databaseExport($pathFile);
        }

        if ($status != 0) {
            $this->lastCommandFailed = true;
        }

        return $pathFile;
    }

    /**
     * @param $pathFile
     * @return mixed|string
     * @throws \Exception
     */
    public function importSql($pathFile)
    {
        if (!file_exists($pathFile)) {
            throw new \Exception('File not exists.', 1456234084);
        }

        if ($this->environment->isSshTunnel()) {
            $status = $this->tunneledDatabaseImport($pathFile);
        } else {
            $status = $this->databaseImport($pathFile);
        }

        if ($status != 0) {
            $this->lastCommandFailed = true;
        }

        return $status;
    }

    /**
     * @return boolean
     */
    public function isLastCommandFailed()
    {
        return $this->lastCommandFailed;
    }
}