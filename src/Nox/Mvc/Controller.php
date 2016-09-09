<?php

namespace Nox\Mvc;

use Nox\Core\Base;
use Nox\Exceptions\ControllerException;
use Nox\Http\Request;
use Nox\Helpers\StringHelper;

abstract class Controller extends Base
{
    /** @var \Nox\Mvc\View */
    protected $view;

    /** @var \Nox\Core\Application */
    protected $app;

    public function __construct()
    {
        parent::__construct();
        
        $this->view = new View(StringHelper::getBaseClassName(self::class));
    }

    /**
     * @param $action
     * @param Request $request
     */
    public function action($action, Request $request)
    {
        $methodName = 'action' . $action;
        $this->$methodName($request);

        // Если после выполнения действия был редирект, то шаблон рендерить
        // не надо
        if (!$this->app->router->wasRedirected) {
            $this->view->display($action);
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @throws ControllerException
     */
    public function __call($name, $arguments)
    {
        throw new ControllerException('Action ' . $name . ' is missing!');
    }
}
