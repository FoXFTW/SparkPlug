<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug;

use App\SparkPlug\Response\ResponseInterface;
use App\SparkPlug\Request\Request;
use App\SparkPlug\Response\ViewResponse;
use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;

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
        // TODO currently only needed in testing, fix
        if (is_null(static::$instance)) {
            return new Application(__DIR__);
        }

        return static::$instance;
    }

    public function singleton(string $className): void
    {
        $this->resolvedSingletons[$className] = new $className();
    }

    public function make(string $className, ?bool $new = false)
    {
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
            $router->match($request);
        } catch (RouteNotFoundException $e) {
            return new ViewResponse('errors.404');
        }
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }
}
