<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:35
 */

namespace App\SparkPlug\Exceptions;

use App\SparkPlug\Views\Exceptions\ViewNotFoundException;
use Exception;

class Handler
{
    public static function printException(Exception $e): string
    {
        return "Exception: ".get_class($e)."\nMessage:\n".htmlentities(
                $e->getMessage()
            )."\nStack trace:\n{$e->getTraceAsString()}\n";
    }

    public static function handleException(Exception $e)
    {
        $class = get_class($e);

        switch ($class) {
            case ViewNotFoundException::class:
                echo static::convertExceptionToHtml($e);
                break;

            default:
                self::printException($e);
                break;
        }
    }

    private static function convertExceptionToHtml(Exception $e): string
    {
        $content = "<h1>Exception: ".get_class($e)."</h1>".
            "<h3>Message:</h3>".htmlentities($e->getMessage()).
            "<h3>Stack trace:</h3>".
            "<pre>{$e->getTraceAsString()}</pre>";

        return $content;
    }
}
