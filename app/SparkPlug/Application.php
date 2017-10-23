<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug;

use App\SparkPlug\Response\ResponseInterface;
use App\SparkPlug\Request\Request;

class Application
{
    private static $instance;
    private $basePath;
    private $resolvedClasses = [];

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;

        static::$instance = $this;
    }

    public static function getInstance(): Application
    {
        if (is_null(static::$instance)) {
            return new Application(__DIR__);
        }

        return static::$instance;
    }

    public function singleton(string $className): void
    {
        $this->resolvedClasses[$className] = new $className();
    }

    public function make(string $className, ?bool $new = false)
    {
        if (isset($this->resolvedClasses[$className]) && !$new) {
            return $this->resolvedClasses[$className];
        }

        return new $className();
    }

    public function handle(Request $request): ResponseInterface
    {

    }
}
