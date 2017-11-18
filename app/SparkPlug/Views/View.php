<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:03
 */

namespace App\SparkPlug\Views;

use App\SparkPlug\Response\ResponseInterface;
use App\SparkPlug\Routing\Router;
use App\SparkPlug\Views\Exceptions\ViewNotFoundException;

class View implements ViewInterface, ResponseInterface
{
    const TEMPLATE_EXTENSION = '.tmplt.php';
    const TEMPLATE_PATH = '/resources/views/';

    private $name;
    private $rawContent;
    private $httpCode;
    private $renderedView;

    public function __construct(string $name, int $httpCode = 200)
    {
        $this->name = str_replace('.', DIRECTORY_SEPARATOR, $name).static::TEMPLATE_EXTENSION;

        ob_start();
        include app()->getBasePath().static::TEMPLATE_PATH.$this->name;
        $content = ob_get_clean();

        if ($content === false) {
            throw new ViewNotFoundException("View {$this->name} not found!");
        }

        $this->rawContent = $content;
        $this->httpCode = $httpCode;

        $this->renderView();
    }

    public function getContent(): string
    {
        return $this->renderedView;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * Send the rendered Response to the Browser
     */
    public function send(): void
    {
        http_response_code($this->getHttpCode());
        echo $this->getContent();
    }

    private function renderView(): void
    {
        $this->renderRoutes();
        $this->renderSubViews();

        if (!preg_match("/@use\(\'([a-z]+)\'\)/", $this->rawContent, $template)) {
            $this->renderedView = $this->rawContent;

            return;
        }

        $template = new View($template[1]);
        $template = $template->getContent();
        $sets = $this->getSetsFromView();


        foreach ($sets as $key => $value) {
            $template = str_replace("@var('{$key}')", $value, $template);
        }


        $this->renderedView = $template;
    }

    private function renderRoutes(): void
    {
        $routeNames = $this->getRoutesFromView();

        foreach ($routeNames as $routeName) {
            /** @var Router $router */
            $router = app()->make(Router::class);
            $route = $router->findByName($routeName);

            $this->rawContent = str_replace("@route('{$routeName}')", $route->getRoute(), $this->rawContent);
        }
    }

    private function renderSubViews(): void
    {
        $subTemplates = $this->getSubTemplatesFromView();

        foreach ($subTemplates as $subTemplate) {
            $view = new View($subTemplate);
            $this->rawContent = str_replace("@include('{$subTemplate}')", $view->getContent(), $this->rawContent);
        }
    }

    private function getSetsFromView(): array
    {
        $simpleSets = [];
        $content = [];

        preg_match_all("/@set\(\'([\w-]+)\',\s?\'([\w\s-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 3) {
            $simpleSets = array_combine($matches[1], $matches[2]);
        }


        preg_match_all("/@set\(\'([\w-]+)\'\)(.*?)@endset/s", $this->rawContent, $matches);

        if (count($matches) === 3) {
            $content = array_combine($matches[1], $matches[2]);
        }

        $vars = array_replace($simpleSets, $content);

        return $vars;
    }

    private function getRoutesFromView(): array
    {
        preg_match_all("/@route\(\'([\w-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 2) {
            return $matches[1];
        }

        return [];
    }

    private function getSubTemplatesFromView(): array
    {
        preg_match_all("/@include\(\'([\w-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 2) {
            return $matches[1];
        }

        return [];
    }
}
