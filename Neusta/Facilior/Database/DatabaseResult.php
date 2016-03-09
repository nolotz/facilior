<?php
namespace Neusta\Facilior\Database;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 09.03.2016
 * Time: 08:05
 */
class DatabaseResult
{
    /**
     * @var array
     */
    protected $shellResults = [];

    /**
     * @var bool
     */
    protected $failed = false;

    /**
     * DatabaseResult constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return array
     */
    public function getShellResults()
    {
        return $this->shellResults;
    }

    /**
     * @param array $shellResults
     */
    public function setShellResults($shellResults)
    {
        $this->shellResults = $shellResults;
    }

    /**
     * @return boolean
     */
    public function isFailed()
    {
        return $this->failed;
    }

    /**
     * @param boolean $failed
     */
    public function setFailed($failed)
    {
        $this->failed = $failed;
    }

    /**
     * @param $shellResult
     */
    public function addShellResults($shellResult)
    {
        $this->shellResults[] = $shellResult;
    }
}
