<?php declare(strict_types = 1);

/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug\Request;

use InvalidArgumentException;

class Request implements RequestInterface
{
    private $uri;
    private $requestMethod;
    private $getVars = [];
    private $postVars = [];

    private function __construct(array $data)
    {
        $this->uri = $data['uri'];
        $this->requestMethod = $data['method'];
        $this->getVars = $data['get'];
        $this->postVars = $data['post'];
    }


    public static function capture(): Request
    {
        $data = [
            'uri'    => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            'method' => $_SERVER['REQUEST_METHOD'],
            'post'   => [],
            'get'    => [],
        ];

        foreach ($_POST as $key => $value) {
            $data['post'][$key] = $value;
        }

        foreach ($_GET as $key => $value) {
            $data['get'][$key] = $value;
        }

        return static::createFromArray($data);
    }

    public static function createFromArray(array $data): Request
    {
        if (!isset($data['uri']) || !isset($data['method'])) {
            throw new InvalidArgumentException('URI and METHOD key are required');
        }

        return new Request($data);
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    public function get(string $key)
    {
        if (isset($this->postVars[$key])) {
            return $this->postVars[$key];
        }

        if (isset($this->getVars[$key])) {
            return $this->getVars[$key];
        }

        return false;
    }
}
