<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:16
 */

namespace App\SparkPlug\Routing;


use App\SparkPlug\Request\Request;

class Router
{
    public const CONTROLLER_NS = 'App\Controllers\\';
    public const VERBS = ['GET', 'POST'];

    public static $routes = [
        /** @var  \App\SparkPlug\Routing\RoutingCollection */
        'GET' => null,
        /** @var  \App\SparkPlug\Routing\RoutingCollection */
        'POST' => null,
    ];

    public static function match(Request $request)
    {
        // ToDo implement Match
    }

    public static function get(string $route, $options): Route
    {
        return static::addRoute('GET', $route, $options);
    }

    public static function post(string $route, $options): Route
    {
        return static::addRoute('POST', $route, $options);
    }

    private static function addRoute(string $method, string $route, $options): Route
    {
        static::init();

        $route = new Route($route, $options);

        switch ($method) {
            case 'GET':
                static::$routes['GET']->add($route);
                break;

            case 'POST':
                static::$routes['POST']->add($route);
                break;
        }

        return $route;
    }

    private static function init()
    {
        if (static::$routes['GET'] === null) {
            static::$routes['GET'] = new RoutingCollection();
        }

        if (static::$routes['POST'] === null) {
            static::$routes['POST'] = new RoutingCollection();
        }
    }
}
