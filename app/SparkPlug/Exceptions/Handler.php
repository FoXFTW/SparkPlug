<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:35
 */

namespace App\SparkPlug\Exceptions;

use App\SparkPlug\Views\Exceptions\ViewNotFoundException;
use Exception;

/**
 * Class Handler
 * Verarbeitet Exceptions der App
 *
 * @package App\SparkPlug\Exceptions
 */
class Handler
{
    /**
     * Konvertiert eine gegebene Exception zu einem string
     *
     * @param \Exception $e Zu verarbeitende Exception
     *
     * @return string
     */
    public static function printException(Exception $e): string
    {
        return "Exception: ".get_class($e)."\nMessage:\n".htmlentities(
                $e->getMessage()
            )."\nStack trace:\n{$e->getTraceAsString()}\n";
    }

    /**
     * Führt anhand des Exceptiontyps verschiedene Aktionen aus
     *
     * @param \Exception $e Zu verarbeitende Exception
     */
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

    /**
     * Rendered eine gegebene Exception zu HTML
     *
     * @param \Exception $e zu verarbeitende Exception
     *
     * @return string
     */
    private static function convertExceptionToHtml(Exception $e): string
    {
        $content = "<h1>Exception: ".get_class($e)."</h1>".
            "<h3>Message:</h3>".htmlentities($e->getMessage()).
            "<h3>Stack trace:</h3>".
            "<pre>{$e->getTraceAsString()}</pre>";

        return $content;
    }
}
