<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:32
 */

namespace App\SparkPlug\Views;

interface ViewInterface
{
    public function getContent(): string;

    public function getHttpCode(): int;
}
