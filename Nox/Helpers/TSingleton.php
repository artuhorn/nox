<?php

namespace Nox\Helpers;

trait TSingleton
{
    private static $instance = null;
    
    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    protected function __wakeup()
    {

    }

    /**
     * @return static
     */
    public static function instance()
    {
        if (static::$instance == null) {
            static::$instance = new static();
        }
        
        return static::$instance;
    }
}
