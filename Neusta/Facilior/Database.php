<?php
namespace Neusta\Facilior;

use Neusta\Facilior\Database\DatabaseResult;
use Neusta\Facilior\Database\ExportDatabaseResult;
use Neusta\Facilior\Services\ConsoleService;
use Neusta\Facilior\Services\FileService;
use Neusta\Facilior\Services\ShellService;
use Neusta\Facilior\Shell\ShellResult;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 05.02.2016
 * Time: 09:52
 */

class Database
{
    /**
     * @var FileService
     */
    protected $fileService;

    /**
     * @var Environment|null
     */
    protected $environment;

    /**
     * @var ConsoleService|null
     */
    protected $consoleOutput;

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
     * @return array
     * @throws \Exception
     */
    protected function tunneledDatabaseExport($destinationFile)
    {
        $command = 'ssh -l ##SSHUSER## ##SSHHOST## "mysqldump --add-drop-table -u ##MYSQLUSER## ' .
            $this->getSingleTransactionParam() .
            ' -p##MYSQLPASS## -h ##MYSQLHOST## --port ##MYSQLPORT## ##MYSQLDB## | ' .
            'gzip -3 -c" > ##DESTFILE##';

        $result = $this->shellService->execute($command, array(
            'SSHUSER'      => $this->environment->getSshUsername(),
            'SSHHOST'      => $this->environment->getSshHost(),
            'MYSQLUSER'    => $this->environment->getUsername(),
            'MYSQLPASS'    => $this->environment->getPassword(),
            'MYSQLDB'      => $this->environment->getDatabase(),
            'MYSQLHOST'     =>  $this->environment->getHost(),
            'MYSQLPORT'     =>  $this->environment->getPort(),
            'DESTFILE'      => $destinationFile . '.gz'
        ));

        $this->fileService->gunzip($destinationFile . '.gz', $destinationFile);

        return [$result];
    }

    /**
     * @param $destinationFile
     * @return array
     * @throws \Exception
     */
    protected function databaseExport($destinationFile)
    {
        $command = 'mysqldump --add-drop-table -u ##MYSQLUSER## ' .
            $this->getSingleTransactionParam() .
            ' --password=##MYSQLPASS## -h ##MYSQLHOST## --port ##MYSQLPORT## ##MYSQLDB## > ##DESTFILE##';

        $result = $this->shellService->execute($command, array(
            'MYSQLUSER'    => $this->environment->getUsername(),
            'MYSQLPASS'    => $this->environment->getPassword(),
            'MYSQLDB'      => $this->environment->getDatabase(),
            'MYSQLHOST'     =>  $this->environment->getHost(),
            'MYSQLPORT'     =>  $this->environment->getPort(),
            'DESTFILE'      => $destinationFile
        ));

        return [$result];
    }

    /**
     * @param $sourceFile
     * @return array
     * @throws \Exception
     */
    protected function tunneledDatabaseImport($sourceFile)
    {
        //Create Dump Name Sql
        $dumpName = uniqid(time() . '_facilior_', true);

        //Zip the File
        $this->fileService->gzip($sourceFile, $sourceFile . '.gz');

        $baseSourceFile = basename($sourceFile . '.gz');

        $scpCommand = 'scp ##SOURCEFILE## ##SSHUSER##@##SSHHOST##:~/##DUMPNAME##';
        $scpResult = $this->shellService->execute($scpCommand, array(
            'SOURCEFILE' => './.facilior/temp/' . $baseSourceFile,
            'SSHUSER'    => $this->environment->getSshUsername(),
            'SSHHOST'    => $this->environment->getSshHost(),
            'DUMPNAME'   => $dumpName . '.gz'
        ));

        if ($scpResult->getExitCode() != 0) {
            return [$scpResult];
        }

        $sshCommand = 'ssh -l ##SSHUSER## ##SSHHOST## "gunzip ##DUMPNAME##.gz ' .
            '&& mysql -h ##MYSQLHOST## -u ##MYSQLUSER## -p##MYSQLPASS## --port ##MYSQLPORT## ##MYSQLDB## < ##DUMPNAME## ' .
            '&& rm ##DUMPNAME##"';
        $sshResult = $this->shellService->execute($sshCommand, array(
            'SSHUSER'   =>  $this->environment->getSshUsername(),
            'SSHHOST'   =>  $this->environment->getSshHost(),
            'DUMPNAME'  =>  $dumpName,
            'MYSQLHOST' =>  $this->environment->getHost(),
            'MYSQLUSER' =>  $this->environment->getUsername(),
            'MYSQLPASS' =>  $this->environment->getPassword(),
            'MYSQLPORT' =>  $this->environment->getPort(),
            'MYSQLDB'   =>  $this->environment->getDatabase()
        ));

        return [$sshResult, $scpResult];
    }

    /**
     * @param $sourceFile
     * @return array
     * @throws \Exception
     */
    protected function databaseImport($sourceFile)
    {
        $command = 'mysql -h ##MYSQLHOST## -u ##MYSQLUSER## -p##MYSQLPASS## --port ##MYSQLPORT## ##MYSQLDB## < ##SOURCEFILE##' .
            '&& rm ##SOURCEFILE##';
        $result = $this->shellService->execute($command, array(
            'MYSQLHOST' =>  $this->environment->getHost(),
            'MYSQLUSER' =>  $this->environment->getUsername(),
            'MYSQLPASS' =>  $this->environment->getPassword(),
            'MYSQLPORT'   =>  $this->environment->getPort(),
            'MYSQLDB'   =>  $this->environment->getDatabase(),
            'SOURCEFILE'=>  $sourceFile
        ));

        return [$result];
    }

    /**
     * @return ExportDatabaseResult
     * @throws \Exception
     */
    public function exportSql()
    {
        $pathFile = $this->fileService->createTempFile();

        if ($this->environment->isSshTunnel()) {
            $shellResults = $this->tunneledDatabaseExport($pathFile);
        } else {
            $shellResults = $this->databaseExport($pathFile);
        }

        $databaseResult = new ExportDatabaseResult();
        $databaseResult->setShellResults($shellResults);
        $databaseResult->setFailed($this->checkResultsAreFailed($shellResults));
        $databaseResult->setPath($pathFile);

        return $databaseResult;
    }

    /**
     * @param $pathFile
     * @return DatabaseResult
     * @throws \Exception
     */
    public function importSql($pathFile)
    {
        if (!file_exists($pathFile)) {
            throw new \Exception('File not exists.', 1456234084);
        }

        if ($this->environment->isSshTunnel()) {
            $shellResults = $this->tunneledDatabaseImport($pathFile);
        } else {
            $shellResults = $this->databaseImport($pathFile);
        }

        $databaseResult = new DatabaseResult();
        $databaseResult->setShellResults($shellResults);
        $databaseResult->setFailed($this->checkResultsAreFailed($shellResults));

        return $databaseResult;
    }

    /**
     * @param array $results
     * @return bool
     */
    protected function checkResultsAreFailed($results)
    {
        $isFailed = false;

        foreach ($results as $result) {
            /** @var ShellResult $result */
            if ($result->getExitCode() != 0) {
                $isFailed = true;
            }
        }

        return $isFailed;
    }

    /**
     * Generate parameters to use single transaction for InnoDB tables
     * and disable MyISAM table lock
     *
     * @return string
     */
    protected function getSingleTransactionParam(): string
    {
        if($this->environment->isSingleTransaction() === false) {
            return '';
        }

        return '--lock-tables=false --single-transaction';
    }
}
