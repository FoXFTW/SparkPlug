<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:23
 */

namespace App\SparkPlug\Response;

use App\SparkPlug\Views\View;

/**
 * Class ViewResponse returns a rendered View
 * @package App\SparkPlug\Response
 */
class ViewResponse implements ResponseInterface
{
    /** @var  \App\SparkPlug\Views\View */
    private $view;

    public function __construct(string $viewName)
    {
        $this->view = new View($viewName);
    }

    /**
     * Send the rendered Response zo zhe Browser
     */
    public function send(): void
    {
        http_response_code($this->view->getHttpCode());
        echo $this->view->getContent();
    }
}
