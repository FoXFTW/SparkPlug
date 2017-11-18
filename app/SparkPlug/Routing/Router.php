<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:16
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Request\RequestInterface;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;

class Router
{
    public const CONTROLLER_NAMESPACE = 'App\Controllers\\';
    public const VERBS = ['GET', 'POST'];

    /** @var \App\SparkPlug\Routing\RoutingCollection[] */
    private $routes = [];

    public function __construct()
    {
        foreach (static::VERBS as $verb) {
            $this->routes[$verb] = new RoutingCollection();
        }
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function findByName(string $name): Route
    {
        foreach ($this->routes as $routingCollection) {
            foreach ($routingCollection as $route) {
                if ($route->getName() === $name) {
                    return $route;
                }
            }
        }

        throw new RouteNotFoundException("Named Route '{$name}' not found!");
    }

    public function match(RequestInterface $request): Route
    {
        if (!in_array($request->getRequestMethod(), static::VERBS)) {
            throw new RouteNotFoundException();
        }

        try {
            /** @var \App\SparkPlug\Routing\Route $route */
            $route = $this->routes[$request->getRequestMethod()]->find($request->getUri());
        } catch (RouteNotFoundException $e) {
            foreach ($this->routes[$request->getRequestMethod()] as $route) {
                if (preg_match(RouteStringConverter::toRegex($route), $request->getUri())) {
                    return $route;
                }
            }
            throw new RouteNotFoundException();
        }

        return $route;
    }

    public function get(string $route, $options): Route
    {
        return $this->addRoute('GET', $route, $options);
    }

    public function post(string $route, $options): Route
    {
        return $this->addRoute('POST', $route, $options);
    }

    private function addRoute(string $method, string $route, $options): Route
    {
        $route = new Route($route, $options);

        $this->routes[$method]->add($route);

        return $route;
    }
}
