<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:20
 */

namespace App\SparkPlug\Routing;

use App\SparkPlug\Routing\Exceptions\InvalidActionException;
use App\SparkPlug\Routing\Exceptions\MissingActionException;

/**
 * Class Route
 *
 * @package App\SparkPlug\Routing
 */
class Route
{
    /** @var string Name der Route */
    private $name = '<<Nameless>>';
    /** @var string Name des Controllers */
    private $controller;
    /** @var string Methode des Controllers */
    private $method;
    /** @var array Argumente des Controllers */
    private $arguments = [];
    /** @var string Route */
    private $route;
    /** @var array|string Unverarbeitete Optionen */
    private $rawOptions = [];

    /**
     * Route constructor.
     *
     * @param string       $route   URI der Route
     * @param array|string $options Optionen der Route
     */
    public function __construct(string $route, $options)
    {
        if (substr($route, 0, 1) !== '/') {
            $route = '/'.$route;
        }

        $this->route = strtolower($route);

        if (is_string($options)) {
            $this->rawOptions['action'] = $options;
        }

        if (is_array($options)) {
            $this->rawOptions = $options;
        }

        $this->parseOptions();
    }

    /**
     * Setzt Namen der Route
     *
     * @param string $name
     *
     * @return \App\SparkPlug\Routing\Route
     */
    public function name(string $name): Route
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Prüft ob Controller Argumente benötigt
     *
     * @return bool
     */
    public function hasArgs(): bool
    {
        return !empty($this->arguments);
    }

    /**
     * Gibt gespeicherte Argumente zurück
     *
     * @return array
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * Setzt Argumente der Route
     * Konvertiert Strings welche Int/Floats sind zu ihren entsprechenden Werten
     *
     * @param array|string $args
     */
    public function setArguments($args): void
    {
        if (!is_array($args)) {
            $args = [$args];
        }

        foreach ($args as $key => $arg) {
            if (is_numeric($arg)) {
                $float = floatval($arg);
                if ($float && intval($float) != $float) {
                    $args[$key] = floatval($arg);
                } else {
                    $args[$key] = intval($arg);
                }
            }
        }

        $this->arguments = $args;
    }

    /**
     * Gibt Namen der Route zurück
     *
     * @return null|string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Gibt URI der Route zurück
     *
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * Gibt Namen des Controller der Route zurück
     *
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * Gibt Name der Methode des Controllers zurück
     *
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "{$this->name}: {$this->controller}@{$this->method}";
    }

    /**
     * @throws \App\SparkPlug\Routing\Exceptions\InvalidActionException
     * @throws \App\SparkPlug\Routing\Exceptions\MissingActionException
     */
    private function parseOptions(): void
    {
        $this->parseAction();
    }

    /**
     * @throws \App\SparkPlug\Routing\Exceptions\InvalidActionException
     * @throws \App\SparkPlug\Routing\Exceptions\MissingActionException
     */
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
