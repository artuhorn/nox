<?php

namespace Nox\Core;

use Nox\Exceptions\ApplicationException;
use Nox\Exceptions\PageNotFoundException;
use Nox\Helpers\TSingleton;
use Nox\Helpers\StringHelper;
use Nox\Routing\Router;

/**
 * Class Application
 * @package Nox\Core
 */
class Application
{
    use TSingleton;

    /** @var \Nox\Core\Config */
    public $config;

    /** @var \Nox\Routing\Router */
    public $router;

    /** @var \Nox\Core\Request */
    public $request;
    /** @var bool */
    public $debugMode = true;
    /** @var string */
    protected $protectedPath = '';
    /** @var string */
    protected $publicPath = '';

    public function getPublicPath()
    {
        return $this->publicPath;
    }

    public function setPublicPath(string $path)
    {
        $this->publicPath = StringHelper::cutLastSlash(realpath($path));
        return $this;
    }

    public function init()
    {
        if (empty($this->publicPath)) {
            throw new ApplicationException('Empty PUBLIC PATH variable');
        }
        if (empty($this->protectedPath)) {
            throw new ApplicationException('Empty PROTECTED PATH variable');
        }

        $this->setConfig(Config::instance())
             ->setRouting(Router::instance())
             ->setRequest(Request::instance());

        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        $this->request->init();
    }

    public function setRouting(Router $router)
    {
        $this->router = $router;
        return $this;
    }

    public function setConfig(Config $config)
    {
        $this->config = $config;
        $config->templatesPath = $this->getProtectedPath() . '/Templates/';
        $config->cachePath = $this->getProtectedPath() . '/Cache/';

        return $this;
    }

    public function getProtectedPath()
    {
        return $this->protectedPath;
    }

    public function setProtectedPath(string $path)
    {
        $this->protectedPath = StringHelper::cutLastSlash(realpath($path));
        return $this;
    }

    public function run()
    {
        try {
            $this->router->route($this->request);
        } catch (PageNotFoundException $e) {
            echo '<h1>Page not found</h1>';
        } catch (\Exception $e) {
            if ($this->debugMode) {
                var_dump($e);
            }
        }
    }
}
