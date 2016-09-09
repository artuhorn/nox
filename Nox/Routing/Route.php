<?php

namespace Nox\Routing;

use Nox\Helpers\StringHelper;

/**
 * Class Route
 * @package App
 */
class Route
{
    /** @var string */
    protected $route;

    /** @var string */
    public $controller;

    /** @var string */
    public $action;

    /** @var array|string[] */
    public $params = [];

    /**
     * Route constructor
     * @param string $route
     * @param string $controller
     * @param string $action
     */
    public function __construct(string $route, string $controller, string $action)
    {
        $this->route = StringHelper::cutLastSlash($route);
        $this->controller = $controller;
        $this->action = $action;

        $this->parseParams();
    }

    /**
     * Проверяет подходит ли данный маршрут к переданному в качестве
     * параметра адресу
     *
     * @param string $url
     * @return bool
     */
    public function matches(string $url): bool
    {
        // Если в конце $url есть '/', то убираем его
        $url = StringHelper::cutLastSlash($url);

        // Заменяем в маршруте параметры вида :param на регулярное выражение
        $regexp = preg_replace('~(:[^\/]+)~', '(.[^\/]*)', $this->route);

        $matches = [];
        preg_match_all('~^' . $regexp . '$~', $url, $matches);
        if (count($matches[0]) === 0) {
            return false;
        }

        // Каждый элемент массива $matches - тоже массив, поэтому необходимо
        // привести все данные в линейный вид
        $paramValues = [];
        array_walk_recursive($matches, function ($value) use (&$paramValues, $url) {
            // Первое значение необходимо пропустить, т.к. это $url, по которому
            // выполнялась проверка на соответствие
            if ($value == $url) {
                return;
            }
            array_push($paramValues, $value);
        });

        // Переносим значения из массива $paramValues, в котором содержатся значения параметров,
        // в итоговый массив объекта params
        // Порядок следования ключей в массиве $params соответствует порядку значений этих ключей
        // в массиве $paramValues
        foreach ($this->params as $key => $value) {
            // $value - индекс ключа, заполненный при парсинге маршрута
            $this->params[$key] = $paramValues[$value] ?? $value;
        }

        return true;
    }

    /**
     * Переносит параметры маршрута (/path/:param1/:param2) в ключи массива $this->params
     */
    protected function parseParams()
    {
        $this->params = [];

        $matches = [];
        preg_match_all('~:([^\/]+)~', $this->route, $matches);

        $params = $matches[1];
        if (count($params) > 0) {
            // $index - ключ, по которому потом будет получено истинное значение параметра в методе matches
            $index = 0;
            foreach ($params as $paramName) {
                $this->params[$paramName] = $index++;
            }
        }
    }
}
