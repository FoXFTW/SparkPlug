<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:16
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Request\RequestInterface;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;

/**
 * Class Router
 * Router, welcher Anfragen zu Routen matched
 *
 * @package App\SparkPlug\Routing
 */
class Router
{
    public const CONTROLLER_NAMESPACE = 'App\Controllers\\';
    public const VERBS = ['GET', 'POST'];

    /** @var \App\SparkPlug\Routing\RoutingCollection[] */
    private $routes = [];

    /**
     * Router constructor.
     */
    public function __construct()
    {
        foreach (static::VERBS as $verb) {
            $this->routes[$verb] = new RoutingCollection();
        }
    }

    /**
     * Gibt gespeicherte Routen zurück
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Durchsucht Collections nach Name
     *
     * @param string $name Name der Route
     *
     * @return \App\SparkPlug\Routing\Route
     * @throws \App\SparkPlug\Routing\Exceptions\RouteNotFoundException
     */
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

    /**
     * Matcht einen Request gegen gespeicherte Routen
     *
     * @param \App\SparkPlug\Request\RequestInterface $request
     *
     * @return \App\SparkPlug\Routing\Route
     * @throws \App\SparkPlug\Routing\Exceptions\RouteNotFoundException
     */
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
                if (preg_match(RouteStringConverter::toRegex($route), $request->getUri(), $match)) {
                    if (isset($match[1])) {
                        array_shift($match);
                        $route->setArguments($match);
                    }

                    return $route;
                }
            }
            throw new RouteNotFoundException();
        }

        return $route;
    }

    /**
     * Fügt neue GET Route hinzu
     *
     * @param string       $route   URI
     * @param array|string $options Optionen
     *
     * @return \App\SparkPlug\Routing\Route
     */
    public function get(string $route, $options): Route
    {
        return $this->addRoute('GET', $route, $options);
    }

    /**
     * Fügt neue POST Route hinzu
     *
     * @param string       $route   URI
     * @param array|string $options Optionen
     *
     * @return \App\SparkPlug\Routing\Route
     */
    public function post(string $route, $options): Route
    {
        return $this->addRoute('POST', $route, $options);
    }

    /**
     * @param string $method
     * @param string $route
     * @param        $options
     *
     * @return \App\SparkPlug\Routing\Route
     */
    private function addRoute(string $method, string $route, $options): Route
    {
        $route = new Route($route, $options);

        $this->routes[$method]->add($route);

        return $route;
    }
}
