<?php
namespace Neusta\Facilior\Init;

/**
 * Created by PhpStorm.
 * User: nlotzer
 * Date: 04.02.2016
 * Time: 07:23
 */
class ProjectAlreadyExistsException extends \Exception
{
    protected $message = 'Already exists .facilior directory.';
    protected $code = 0x50726f6a656374416c7265616479457869737473;
}
