<?php
namespace Neusta\Facilior;

use Neusta\Facilior\Services\ConsoleService;
use Neusta\Facilior\Services\FileService;
use Neusta\Facilior\Services\ShellService;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 05.02.2016
 * Time: 09:52
 */

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
     * @var ShellService
     */
    protected $shellService;

    /**
     * Database constructor.
     * @param Environment $environment
     */
    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
        $this->consoleOutput = new ConsoleService();
        $this->fileService = new FileService();
        $this->shellService = new ShellService();
    }

    /**
     * @param $destinationFile
     * @return string
     */
    protected function tunneledDatabaseExport($destinationFile)
    {

        $command = 'ssh -l ##SSH_USER## ##SSH_HOST## "mysqldump --add-drop-table -u ##MYSQL_USER##
        --password=\'##MYSQL_PASS##\' ##MYSQL_DB## | gzip -3 -c" > ##DESTFILE## && gunzip ##DESTFILE##';

        $output = $this->shellService->execute($command, array(
            'SSH_USER'      => $this->environment->getSshUsername(),
            'SSH_HOST'      => $this->environment->getSshHost(),
            'MYSQL_USER'    => $this->environment->getUsername(),
            'MYSQL_PASS'    => $this->environment->getPassword(),
            'MYSQL_DB'      => $this->environment->getDatabase(),
            'DESTFILE'      => $destinationFile
        ));

        $this->consoleOutput->log(implode(PHP_EOL, $output));
        return $this->shellService->getLastExitCode();
    }

    /**
     * @param $destinationFile
     * @return mixed
     */
    protected function databaseExport($destinationFile)
    {
        $command = 'mysqldump --add-drop-table -u ##MYSQL_USER##
        --password=\'##MYSQL_PASS##\' ##MYSQL_DB## > ##DESTFILE##';

        $output = $this->shellService->execute($command, array(
            'MYSQL_USER'    => $this->environment->getUsername(),
            'MYSQL_PASS'    => $this->environment->getPassword(),
            'MYSQL_DB'      => $this->environment->getDatabase(),
            'DESTFILE'      => $destinationFile
        ));

        $this->consoleOutput->log(implode(PHP_EOL, $output));
        return $this->shellService->getLastExitCode();
    }

    /**
     * @param $sourceFile
     * @return string
     */
    protected function tunneledDatabaseImport($sourceFile)
    {
        $gzipCommand = 'gzip -3 ##SOURCEFILE##';
        $gzipLog = $this->shellService->execute($gzipCommand, array(
            'SOURCEFILE' => $sourceFile
        ));

        $this->consoleOutput->log($gzipLog);

        //Uploading SQL Dump to Remote Host
        $dumpName = uniqid(time() . '_facilior_');

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