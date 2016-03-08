<?php
namespace Neusta\Facilior\Services;



use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileService
{
    const TEMPDIR_NAME = 'temp';

    /**
     * @var bool
     */
    protected $isRemote = false;

    /**
     * FileService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @return void
     */
    public function init()
    {
        $this->createTempFolder();
    }

    /**
     * @return void
     */
    public function cleanup()
    {
        $this->deleteTempFolder();
    }

    /**
     * @return void
     */
    protected function createTempFolder()
    {
        if (file_exists(getcwd() . '/.facilior/') && !file_exists(getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME)) {
            mkdir(getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME);
        }
    }

    /**
     * @return void
     */
    protected function deleteTempFolder()
    {
        if (file_exists(getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME)) {
            $this->rmDir(getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME);
        }
    }

    /**
     * Creates a Temp File and Returns the path
     * @return string
     */
    public function createTempFile()
    {
        $tempFolder = getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME . '/';

        do {
            $fileName = uniqid('facilior_' . time() . '_');
        } while (file_exists($tempFolder . $fileName));

        $finalFile = $tempFolder . $fileName;
        touch($finalFile);

        return $finalFile;
    }


    /**
     * @param $dir
     * @param bool $recursive
     */
    protected function rmDir($dir, $recursive = true)
    {
        if ($recursive) {
            $iterator = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($iterator, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }
        }

        rmdir($dir);
    }
}