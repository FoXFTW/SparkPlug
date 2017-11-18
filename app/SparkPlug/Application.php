<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug;

use App\SparkPlug\Exceptions\ClassNotFoundException;
use App\SparkPlug\Request\Request;
use App\SparkPlug\Response\ResponseInterface;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;
use App\SparkPlug\Views\View;

class Application
{
    private static $instance;
    private $basePath;
    private $resolvedSingletons = [];

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        static::$instance = $this;
    }

    public static function getInstance(): Application
    {
        return static::$instance;
    }

    public function singleton(string $className): void
    {
        $this->resolvedSingletons[$className] = new $className();
    }

    public function make(string $className, bool $new = false)
    {
        if (!class_exists($className)) {
            throw new ClassNotFoundException("Class {$className} not defined or loaded!");
        }

        if (isset($this->resolvedSingletons[$className]) && !$new) {
            return $this->resolvedSingletons[$className];
        }

        return new $className();
    }

    public function handle(Request $request): ResponseInterface
    {
        /** @var \App\SparkPlug\Routing\Router $router */
        $router = $this->make(\App\SparkPlug\Routing\Router::class);

        try {
            /** @var \App\SparkPlug\Routing\Route $route */
            $route = $router->match($request);
        } catch (RouteNotFoundException $e) {
            return new View('errors.404', 404);
        }

        /** @var \App\Controllers\AbstractBaseController $controller */
        $controller = $this->make($route->getController());
        $controller->setRequest($request);

        return call_user_func_array([$controller, $route->getMethod()], $route->getArguments());
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function loadRoutes(): void
    {
        if (!is_dir($this->basePath.'/routes')) {
            throw new \ErrorException('routes Dir is missing');
        }

        $files = glob($this->basePath.DIRECTORY_SEPARATOR.'routes'.DIRECTORY_SEPARATOR.'*.php');

        foreach ($files as $file) {
            require_once $file;
        }
    }
}
