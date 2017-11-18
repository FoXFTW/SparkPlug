<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:33
 */

namespace App\SparkPlug\Views;


use App\SparkPlug\Response\ResponseInterface;

class RawView implements ViewInterface, ResponseInterface
{
    private $content;
    private $httpCode;

    public function setContent(string $content, int $httpCode = 200): void
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
    }

    public function setHttpCode(int $code): void
    {
        $this->httpCode = $code;
    }

    public function getContent(): string
    {
        return $this->content;
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
}
