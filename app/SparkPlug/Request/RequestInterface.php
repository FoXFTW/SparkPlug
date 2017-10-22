<?php
/**
 * User: Hannes
 * Date: 22.10.2017
 * Time: 21:53
 */

namespace App\SparkPlug\Request;

interface RequestInterface
{
    public static function capture(): Request;

    public function getUri(): string;

    public function getRequestMethod(): string;
}