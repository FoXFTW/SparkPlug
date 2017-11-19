<?php declare(strict_types = 1);
/**
 * Created by PhpStorm.
 * User: Hanne
 * Date: 19.11.2017
 * Time: 01:01
 */

namespace App\SparkPlug\Views;

use App\SparkPlug\Response\ResponseInterface;

/**
 * Class AbstractBaseView
 *
 * @package app\SparkPlug\Views
 */
abstract class AbstractBaseView implements ViewInterface, ResponseInterface
{
    /** @var int HTTP Status Code */
    protected $httpCode;

    /**
     * @param int $code
     */
    public function setHttpCode(int $code): void
    {
        $this->httpCode = $code;
    }

    /**
     * HTTP Code des Views
     *
     * @return int http code
     */
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
