<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:33
 */

namespace App\SparkPlug\Views;


class RawView implements ViewInterface
{
    private $content;
    private $httpCode;

    public function setContent(string $content): void
    {
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
}
