<?php
/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 03.02.2016
 * Time: 08:06
 */

namespace Neusta\Facilior;


use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Yaml;

class Config
{

    /**
     * @var array
     */
    protected $arguments = [];


    protected $parser = null;

    /**
     * Config constructor.
     */
    public function __construct($arguments)
    {
        $this->arguments = $arguments;
        $this->parser = new Parser();
    }

    /**
     * Returns Config value of specific key
     * @param $key
     * @return mixed
     */
    public function get($key)
    {

    }

}