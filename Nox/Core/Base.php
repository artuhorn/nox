<?php

namespace Nox\Core;

/**
 * Class Base
 * @package Nox\Core
 */
class Base
{
    /** @var \Nox\Core\Application */
    protected $app;
    
    public function __construct()
    {
        $this->app = Application::instance();
    }
}
