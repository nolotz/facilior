<?php
namespace Neusta\Facilior\Database;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 09.03.2016
 * Time: 08:09
 */


class ExportDatabaseResult extends DatabaseResult
{

    /**
     * @var string
     */
    protected $path = '';

    /**
     * ImportDatabaseResult constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }
}
