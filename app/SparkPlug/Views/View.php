<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:03
 */

namespace App\SparkPlug\Views;

use App\SparkPlug\Views\Exceptions\ViewNotFoundException;

class View implements ViewInterface
{
    const TEMPLATE_EXTENSION = '.tmplt.php';
    const TEMPLATE_PATH = '/resources/views/';

    private $name;
    private $rawContent;
    private $httpCode = 200;

    public function __construct(string $name, int $httpCode = 200)
    {
        $this->name = str_replace('.', DIRECTORY_SEPARATOR, $name).static::TEMPLATE_EXTENSION;

        $content = file_get_contents(app()->getBasePath().static::TEMPLATE_PATH.$this->name);

        if ($content === false) {
            throw new ViewNotFoundException("View {$this->name} not found!");
        }

        $this->rawContent = $content;

        $this->httpCode = $httpCode;
    }

    public function getContent(): string
    {
        return $this->rawContent;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
