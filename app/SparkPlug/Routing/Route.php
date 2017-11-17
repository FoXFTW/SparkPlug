<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:20
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Routing\Exceptions\InvalidActionException;
use App\SparkPlug\Routing\Exceptions\MissingActionException;

class Route
{
    private $name = '';
    private $controller;
    private $method;
    private $route;

    private $rawOptions = [];

    public function __construct(string $route, $options)
    {
        if (substr($route, 0, 1) !== '/') {
            $route = '/'.$route;
        }

        $this->route = $route;

        if (is_string($options)) {
            $this->rawOptions['action'] = $options;
        }

        if (is_array($options)) {
            $this->rawOptions = $options;
        }

        $this->parseOptions();
    }

    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function __toString()
    {
        return "{$this->name}: {$this->controller}@{$this->method}";
    }

    private function parseOptions(): void
    {
        $this->parseAction();
    }

    private function parseAction(): void
    {
        if (!isset($this->rawOptions['action'])) {
            throw new MissingActionException();
        } elseif (!is_string($this->rawOptions['action']) || !str_contains($this->rawOptions['action'], '@')) {
            throw new InvalidActionException();
        }

        $rawAction = explode('@', $this->rawOptions['action']);
        if (count($rawAction) !== 2) {
            throw new InvalidActionException('Provided action has length of '.count($rawAction).' should be 2');
        }

        $this->controller = Router::CONTROLLER_NAMESPACE.$rawAction[0];
        $this->method = $rawAction[1];
    }
}
