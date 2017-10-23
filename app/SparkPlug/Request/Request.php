<?php declare(strict_types = 1);

/**
 * User: Hannes
 * Date: 20.10.2017
 * Time: 17:17
 */

namespace App\SparkPlug\Request;

class Request implements RequestInterface
{
    public static function capture(): Request
    {
        // ToDo implement capture method
    }

    public function getUri(): string
    {
        // TODO: Implement getUri() method.
    }

    public function getRequestMethod(): string
    {
        // TODO: Implement getRequestMethod() method.
    }
}
