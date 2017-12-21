<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug;

use App\SparkPlug\Exceptions\TokenMissMatchException;
use App\SparkPlug\Request\Request;
use App\SparkPlug\Response\ResponseInterface;
use App\SparkPlug\Views\RawView;

/**
 * Application Container
 * Einstiegspunkt in gesamte App
 * Verwaltet in der APp benötigte Singletons und sendet Ausgaben an den Browser
 *
 * @package App\SparkPlug
 */
class Application
{
    /** @var  Application */
    private static $instance;
    private $basePath;
    private $resolvedSingletons = [];
    private $bindings = [];
    /** @var Request */
    private $request;

    /**
     * Application constructor.
     *
     * @param string $basePath Pfad zu App Ordner
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
        static::$instance = $this;
    }

    /**
     * Gibt eine Instanz der App zurück
     *
     * @return \App\SparkPlug\Application
     */
    public static function getInstance(): Application
    {
        return static::$instance;
    }

    /**
     * Bindet ein Interface an eine Implementierung
     *
     * @param string $interface
     * @param string $implementation
     */
    public function bind(string $interface, string $implementation): void
    {
        $this->bindings[$interface] = $implementation;
    }

    /**
     * Registriert einen Klassennamen als Singleton und erstellt eine Instanz
     *
     * @param string $className
     *
     */
    public function singleton(string $className): void
    {
        $this->resolvedSingletons[$className] = $this->make($className);
    }

    /**
     * Registriert einen Klassennamen als Singleton und erstellt eine Instanz mit den gegebenen Argumenten
     *
     * @param string $className
     * @param array  $args
     *
     */
    public function singletonWith(string $className, array $args): void
    {
        $this->resolvedSingletons[$className] = $this->makeWith($className, $args);
    }

    /**
     * Instanziert eine Klasse des gegebenen Namens, oder gibt eine Instanz zurück, wenn Klassenname als Singleton
     * registriert wurde
     *
     * @param string $className Name der Klasse
     * @param bool   $new       Ignoriert registrierte Singletons
     *
     * @return mixed
     */
    public function make(string $className, bool $new = false)
    {
        $className = $this->checkClassNameForBindings($className);

        if (isset($this->resolvedSingletons[$className]) && !$new) {
            return $this->resolvedSingletons[$className];
        }

        return new $className();
    }

    /**
     * Instanziert eine Klasse des gegebenen Namens mit Argumenten, oder gibt eine Instanz zurück, wenn Klassenname als
     * Singleton registriert wurde
     *
     * @param string $className Name der Klasse
     * @param array  $args
     * @param bool   $new       Ignoriert registrierte Singletons
     *
     * @return object
     */
    public function makeWith(string $className, array $args, bool $new = false)
    {
        $className = $this->checkClassNameForBindings($className);

        if (isset($this->resolvedSingletons[$className]) && !$new) {
            return $this->resolvedSingletons[$className];
        }

        $reflection = new \ReflectionClass($className);

        return $reflection->newInstanceArgs($args);
    }

    /**
     * Verarbeitet eingehende Requests
     *
     * @param \App\SparkPlug\Request\Request $request Aktueller Request
     *
     * @return mixed
     * @throws \App\SparkPlug\Routing\Exceptions\RouteNotFoundException
     * @throws \App\SparkPlug\Exceptions\TokenMissMatchException
     * @throws \Exception
     */
    public function handle(Request $request)
    {
        $this->resolvedSingletons[Request::class] = $request;
        $this->request = $request;
        $this->checkCsrfToken();

        /** @var \App\SparkPlug\Routing\Router $router */
        $router = $this->make(\App\SparkPlug\Routing\Router::class);

        /** @var \App\SparkPlug\Routing\Route $route */
        $route = $router->match($request);

        /** @var \App\SparkPlug\Controllers\AbstractController $controller */
        $controller = $this->make($route->getController());
        $controller->setRequest($request);

        $return = call_user_func_array([$controller, $route->getMethod()], $route->getArguments());

        if (!$return instanceof ResponseInterface) {
            return new RawView();
        }

        return $return;
    }

    /**
     * @return string Basepath der App
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * Registriert Routen in den Router
     *
     * @throws \ErrorException
     */
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

    /**
     * Löscht Error aus Session und setzt zuletzt aufgerufene Seite
     */
    public function terminate(): void
    {
        $session = $this->make(Session::class);
        $session->error = null;
        $session->vars = null;
        if (!is_null($this->request)) {
            $session->previous_page = $this->request->getUri();
        }
    }

    /**
     * Checkt ob für eine gegebene Klasse ein Binding besteht
     *
     * @param string $className
     *
     * @return string
     */
    private function checkClassNameForBindings(string $className): string
    {
        if (isset($this->bindings[$className])) {
            $className = $this->bindings[$className];
        }

        return $className;
    }

    /**
     * @throws \App\SparkPlug\Exceptions\TokenMissMatchException
     * @throws \Exception
     */
    private function checkCsrfToken()
    {
        $token = $this->request->get('csrf_token');

        if ($token !== false) {
            if (!hash_equals($token, csrf_token())) {
                throw new TokenMissMatchException('Possible CSRF Attempt<br>'.csrf_token().'<br>'.$token);
            }
        }
    }
}
