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
            return app(\Nolotz\Facilior\Foundation\LoggerFactory::class)->getLogger();
        }

        return app(\Nolotz\Facilior\Foundation\LoggerFactory::class)
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