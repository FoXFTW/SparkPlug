<?php declare(strict_types = 1);

/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug\Request;

use InvalidArgumentException;

/**
 * Class Request
 *
 * @package App\SparkPlug\Request
 */
class Request implements RequestInterface
{
    /** @var string $uri */
    private $uri;
    /** @var string Request Method [GET|POST] */
    private $requestMethod;
    /** @var array GET Vars des Request */
    private $getVars = [];
    /** @var array POST Vars des Request */
    private $postVars = [];
    /** @var array Files */
    private $files = [];

    /**
     * Request constructor.
     *
     * @param array $data Daten des Requests
     */
    private function __construct(array $data)
    {
        $this->uri = $data['uri'];
        $this->requestMethod = $data['method'];
        $this->getVars = $data['get'];
        $this->postVars = $data['post'];
        $this->files = $data['files'];
    }

    /**
     * Erstellt eine Request Instanz mit dem aktuellen HTTP Request
     *
     * @return \App\SparkPlug\Request\Request
     */
    public static function capture(): Request
    {
        $data = [
            'uri'    => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),
            'method' => $_SERVER['REQUEST_METHOD'],
            'post'   => [],
            'get'    => [],
            'files'  => [],
        ];

        foreach ($_POST as $key => $value) {
            $data['post'][$key] = $value;
        }

        foreach ($_GET as $key => $value) {
            $data['get'][$key] = $value;
        }

        foreach ($_FILES as $key => $value) {
            $data['files'][$key] = $value;
        }

        return static::createFromArray($data);
    }

    /**
     * Erstellt Request anhand von Array
     *
     * @param array $data
     *
     * @return \App\SparkPlug\Request\Request
     */
    public static function createFromArray(array $data): Request
    {
        if (!isset($data['uri']) || !isset($data['method'])) {
            throw new InvalidArgumentException('URI and METHOD key are required');
        }

        return new Request($data);
    }

    /**
     * Gibt URI Des Requests zurück
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Gibt Methode des Requests zurück [GET|POST]
     *
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * Durchsucht GET und POST Parameter nach gegebenem Key
     *
     * @param string $key
     *
     * @return mixed
     */
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

    /**
     * Gibt alle POST und GET Variablen zurück
     *
     * @return array
     */
    public function all(): array
    {
        return array_merge($this->getVars, $this->postVars, $this->files);
    }
}
