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
        $this->cleanup();
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
        if (file_exists(getcwd() . '/.facilior/') &&
            !file_exists(getcwd() . '/.facilior/' . FileService::TEMPDIR_NAME)) {
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
        return str_replace("\\", "/", $finalFile);
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

    /**
     * @param $srcFile
     * @param $dstFile
     */
    public function gunzip($srcFile, $dstFile)
    {
        $compressedFile = gzopen($srcFile, "rb");
        $destinationFile = fopen($dstFile, "w");

        while (!gzeof($compressedFile)) {
            $string = gzread($compressedFile, 4096);
            fwrite($destinationFile, $string, strlen($string));
        }
        gzclose($compressedFile);
        fclose($destinationFile);
    }

    /**
     * @param $srcFile
     * @param $dstFile
     * @param int $level
     * @return bool
     */
    public function gzip($srcFile, $dstFile, $level = 3)
    {
        $mode = 'wb' . $level;
        $error = false;
        if ($fpOut = gzopen($dstFile, $mode)) {
            if ($fpIn = fopen($srcFile, 'rb')) {
                while (!feof($fpIn)) {
                    gzwrite($fpOut, fread($fpIn, 1024 * 512));
                }
                fclose($fpIn);
            } else {
                $error = true;
            }
            gzclose($fpOut);
        } else {
            $error = true;
        }

        return $error ? false : $dstFile;
    }
}
