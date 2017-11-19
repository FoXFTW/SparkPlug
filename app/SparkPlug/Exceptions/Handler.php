<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:35
 */

namespace App\SparkPlug\Exceptions;

use Throwable;

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
     * @param \Throwable $e Zu verarbeitende Exception
     */
    public static function printException(Throwable $e): void
    {
        echo "Exception: ".get_class($e)."\nMessage:\n".htmlentities(
                $e->getMessage()
            )."\nStack trace:\n{$e->getTraceAsString()}\n";
    }

    /**
     * Führt anhand des Exceptiontyps verschiedene Aktionen aus
     *
     * @param \Throwable $e Zu verarbeitende Exception
     */
    public static function handleException(Throwable $e)
    {
        $class = get_class($e);

        switch ($class) {
            default:
                self::convertExceptionToHtml($e);
                break;
        }
    }

    /**
     * Rendered eine gegebene Exception zu HTML
     *
     * @param \Throwable $e zu verarbeitende Exception
     */
    private static function convertExceptionToHtml(Throwable $e): void
    {
        $content = "<h1>Exception: ".get_class($e)."</h1>".
            "<h3>Message:</h3>".htmlentities($e->getMessage()).
            "<h3>Stack trace:</h3>".
            "<pre>{$e->getTraceAsString()}</pre>";

        echo $content;
    }
}
