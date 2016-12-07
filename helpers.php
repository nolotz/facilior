<?php

if(!function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param  string  $message
     * @param  array  $context
     * @return \Nolotz\Facilior\Foundation\LoggerFactory|null
     */
    function logger($message = null, $context = array()) {
        if(is_null($message)) {
            return singleton(\Nolotz\Facilior\Foundation\LoggerFactory::class)->getLogger();
        }

        return singleton(\Nolotz\Facilior\Foundation\LoggerFactory::class)
            ->getLogger()
            ->debug($message, $context);
    }
}

if (! function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param null  $make
     * @param array $parameters
     *
     * @return mixed|\Illuminate\Container\Container
     */
    function app($make = null, $parameters = [])
    {
        if (is_null($make)) {
            return \Illuminate\Container\Container::getInstance();
        }

        return \Illuminate\Container\Container::getInstance()->make($make, $parameters);
    }
}

if (! function_exists('singleton')) {
    /**
     * Get the available container instance.
     *
     * @param null  $make
     * @param array $parameters
     *
     * @return mixed|\Illuminate\Container\Container
     */
    function singleton($make = null, $parameters = [])
    {
        return \Nolotz\Facilior\Foundation\Container::getInstance()->make($make, $parameters);
    }
}

if (! function_exists('output')) {

	/**
	 * @return \Nolotz\Facilior\Foundation\OutputManager
	 */
    function output()
    {
    	return singleton(\Nolotz\Facilior\Foundation\OutputManager::class);
    }
}

if (! function_exists('hook')) {

	/**
	 * @return \Nolotz\Facilior\Foundation\HookManager
	 */
    function hook()
    {
    	return singleton(\Nolotz\Facilior\Foundation\HookManager::class);
    }
}
