<?php

namespace Nox\Tests\Routing;

use Nox\Routing\Route;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function testParseRoute()
    {
        $route = new Route('/:param1', '', '');
        $this->assertArrayHasKey('param1', $route->params);

        $route = new Route('/path/:param1/:param2', '', '');
        $this->assertArrayHasKey('param1', $route->params);
        $this->assertArrayHasKey('param2', $route->params);

        $route = new Route('/path/param1/param2', '', '');
        $this->assertArrayNotHasKey('param1', $route->params);
        $this->assertArrayNotHasKey('param2', $route->params);
    }

    /**
     * @depends testParseRoute
     */
    public function testMatchesWithoutParameters()
    {
        $route = new Route('/', '', '');
        $this->assertEquals($route->matches('/'), true);

        $route = new Route('/path', '', '');
        $this->assertEquals($route->matches('/path'), true);
        $this->assertEquals($route->matches('/path/to/page'), false);

        $route = new Route('/path/to/page', '', '');
        $this->assertEquals($route->matches('/path'), false);
        $this->assertEquals($route->matches('/path/to/page'), true);

        $route = new Route('/path/to/page/', '', '');
        $this->assertEquals($route->matches('/path'), false);
        $this->assertEquals($route->matches('/path/to/page'), true);
    }

    /**
     * @depends testParseRoute
     */
    public function testMatchesWithParameters()
    {
        $route = new Route('/:param1', '', '');
        $this->assertEquals($route->matches('/5'), true);
        $this->assertEquals($route->params['param1'], '5');

        $route = new Route('/path/:param1/:param2', '', '');
        $this->assertEquals($route->matches('/path/5/'), false);
        $this->assertEquals($route->matches('/path/5/true'), true);
        $this->assertEquals($route->matches('/path/5/value/20'), false);

        $route = new Route('/path/:param1/:param2/', '', '');
        $this->assertEquals($route->matches('/path/5/'), false);
        $this->assertEquals($route->matches('/path/5/true'), true);
        $this->assertEquals($route->matches('/path/5/value/20'), false);

        $this->assertEquals($route->params['param1'], '5');
        $this->assertEquals($route->params['param2'], 'true');
    }

    /**
     * @depends testParseRoute
     */
    public function testMatchesWithOptionalSlash()
    {
        $route = new Route('/path', '', '');
        $this->assertEquals($route->matches('/path/'), true);

        $route = new Route('/:param', '', '');
        $this->assertEquals($route->matches('/5/'), true);

        $route = new Route('/path/:param1', '', '');
        $this->assertEquals($route->matches('/path/5/'), true);
    }
}
