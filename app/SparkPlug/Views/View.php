<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:03
 */

namespace App\SparkPlug\Views;

use App\SparkPlug\Routing\Router;
use App\SparkPlug\Views\Exceptions\ViewNotFoundException;

/**
 * Class View
 * Verarbeitet SparkPlug View Dateien
 *
 * @package App\SparkPlug\Views
 */
class View extends AbstractBaseView
{
    const TEMPLATE_EXTENSION = '.tmplt.php';

    /** @var string Name der Datei ohne Endung */
    private $name;
    /** @var  string Dateiinhalt */
    private $rawContent;
    /** @var  array Variablen welche in View geladen werden */
    private $variables;
    /** @var  string Gerenderted Inhalt; wird an Browser gesendet */
    private $renderedView;

    /**
     * View constructor.
     *
     * @param string $name     Name der Datei in dot Notation
     * @param int    $httpCode HTTP Status Code
     *
     * @throws \App\SparkPlug\Views\Exceptions\ViewNotFoundException
     */
    public function __construct(string $name, int $httpCode = 200)
    {
        $this->name = str_replace('.', DIRECTORY_SEPARATOR, $name).static::TEMPLATE_EXTENSION;

        if (!is_file(config('view.path')."/{$this->name}")) {
            throw new ViewNotFoundException("View {$this->name} not found!");
        }

        $this->httpCode = $httpCode;
    }

    /**
     * Gibt den gerenderten Inhalt des Views zurück
     *
     * @return string Rendered View
     */
    public function getContent(): string
    {
        $this->renderView();

        return $this->renderedView;
    }

    /**
     * @return string Dateiinhalt des Views
     */
    public function getRawContent(): string
    {
        return $this->getViewFileContent();
    }

    /**
     * Verarbeitet ein AssocArray zu Key Value paaren
     *
     * @param array $vars Array mit zu setzenden Variablen
     */
    public function setVars(array $vars): void
    {
        $this->variables = $vars;
    }

    /**
     * @return string Dateiinhalt mit verarbeitetem PHP-Code
     */
    private function getViewFileContent(): string
    {
        ob_start();
        if (is_array($this->variables)) {
            extract($this->variables);
        }
        include config('view.path')."/{$this->name}";

        return ob_get_clean();
    }

    /**
     * Renders Templatesyntax into HTML
     */
    private function renderView(): void
    {
        $content = $this->getViewFileContent();

        $this->rawContent = $content;

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

    /**
     * @return array Matched Sets @set('name', 'value')
     */
    private function getSetsFromView(): array
    {
        $simpleSets = [];
        $content = [];

        preg_match_all("/@set\(\'([\w-]+)\',\s?\'([\w\s-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 3) {
            $simpleSets = array_combine($matches[1], $matches[2]);
        }


        preg_match_all("/@set\(\'([\w-]+)\'\)([\w\W]+?)@endset/s", $this->rawContent, $matches);

        if (count($matches) === 3) {
            $content = array_combine($matches[1], $matches[2]);
        }

        $vars = array_replace($simpleSets, $content);

        return $vars;
    }

    /**
     * @return array Matched Routes @route('name')
     */
    private function getRoutesFromView(): array
    {
        preg_match_all("/@route\(\'([\w-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 2) {
            return $matches[1];
        }

        return [];
    }

    /**
     * @return array Matched Includes @include('viewName')
     */
    private function getSubTemplatesFromView(): array
    {
        preg_match_all("/@include\(\'([\w\.-]+)\'\)/", $this->rawContent, $matches);

        if (count($matches) === 2) {
            return $matches[1];
        }

        return [];
    }
}
