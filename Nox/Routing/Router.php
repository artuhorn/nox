<?php

namespace Nox\Routing;

use Nox\Http\Request;
use Nox\Exceptions\PageNotFoundException;
use Nox\Helpers\TSingleton;

/**
 * Class Router
 * @package Nox
 */
class Router
{
    use TSingleton;

    /** @var array|\Nox\Routing\Route[] */
    protected $routes = [];
    
    public $wasRedirected = false;

    /**
     * @param string $pattern
     * @param string $controllerClass
     * @param string $action
     */
    public function addRoute(string $pattern, string $controllerClass, string $action)
    {
        $this->routes[] = new Route($pattern, $controllerClass, $action);
    }

    /**
     * Navigates to matched controller
     *
     * @param Request $request
     * @throws PageNotFoundException
     */
    public function route(Request $request)
    {
        $this->wasRedirected = false;
        
        foreach ($this->routes as $route) {
            if ($route->matches($request->url)) {
                $request->params->fromArray($route->params);
                
                /** @var \Nox\Mvc\Controller $controller */
                $controller = new $route->controller();
                $controller->action($route->action, $request);
                return;
            }
        }
        // Route not found
        throw new PageNotFoundException('Requested page not found');
    }

    /**
     * @param string $url
     */
    public function navigate(string $url)
    {
        $this->wasRedirected = true;
        header('Location: ' . $url);
    }
}
