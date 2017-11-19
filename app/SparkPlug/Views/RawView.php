<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:33
 */

namespace App\SparkPlug\Views;

/**
 * Class RawView
 *
 * @package App\SparkPlug\Views
 */
class RawView extends AbstractBaseView implements ViewInterface
{
    private $content;

    /**
     * @param string $content  An den Browser sendbarer Inhalt
     * @param int    $httpCode HTTP Status Code
     */
    public function setContent(string $content, int $httpCode = 200): void
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
    }

    /**
     * @return string The Set Content
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
