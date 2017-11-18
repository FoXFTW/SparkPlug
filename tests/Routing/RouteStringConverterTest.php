<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 23.10.2017
 * Time: 20:45
 */

namespace App\Tests;

use App\SparkPlug\Routing\Router;
use App\SparkPlug\Routing\RouteStringConverter;
use PHPUnit\Framework\TestCase;

class RouteStringConverterTest extends TestCase
{
    private $expectedRegex = [
        '/index'        => '\/index',
        '/user/profile' => '\/user\/profile',
        '/user/[?]'     => '\/user\/[\w]*',
    ];

    /** @var  \App\SparkPlug\Routing\Router */
    private $router;

    /**
     * @covers \App\SparkPlug\Routing\RouteStringConverter
     */
    public function testRegexConversion()
    {
        $regexStart = RouteStringConverter::REGEX_START;
        $regexEnd = RouteStringConverter::REGEX_END;

        foreach ($this->router->getRoutes()['GET'] as $route) {
            $this->assertEquals(
                $regexStart.$this->expectedRegex[$route->getRoute()].$regexEnd,
                RouteStringConverter::toRegex($route)
            );
        }

    }

    protected function setUp()
    {
        $app = require __DIR__.'/../../config/app.php';
        $this->router = app()->make(Router::class, true);
        $this->router->get('/index', 'IndexController@test');
        $this->router->get('/user/profile', 'UserController@test');
        $this->router->get('/user/[?]', 'UserController@test');

        parent::setUp();
    }
}
