<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 24.10.2017
 * Time: 21:35
 */

namespace App\SparkPlug\Exceptions;

use App\SparkPlug\Routing\Exceptions\RouteNotFoundException;
use App\SparkPlug\Validation\Exceptions\ValidationException;
use App\SparkPlug\Views\View;
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
     *
     * @return void|mixed
     */
    public static function handleException(Throwable $e)
    {
        $class = get_class($e);

        if (config('app.debug')) {
            $view = new View('errors.500', 500);
            $view->setVars(['error' => self::convertExceptionToHtml($e)]);
            $view->send();
            die();
        }

        switch ($class) {
            case RouteNotFoundException::class:
                $view = new View('errors.404', 404);
                $view->send();
                break;

            case ValidationException::class:
                session_set('error', $e->getErrors());

                return back();

            case TokenMissMatchException::class:
                $view = new View('errors.session_expired', 401);
                $view->send();
                break;

            default:
                $view = new View('errors.500', 500);
                $view->send();
                break;
        }
    }

    /**
     * Rendered eine gegebene Exception zu HTML
     *
     * @param \Throwable $e zu verarbeitende Exception
     *
     * @return string
     */
    private static function convertExceptionToHtml(Throwable $e): string
    {
        $content = "<h1>Exception: ".get_class($e)."</h1>".
            "<h3>Message:</h3>".htmlentities($e->getMessage()).
            "<h3>Stack trace:</h3>".
            "<pre>{$e->getTraceAsString()}</pre>";

        return $content;
    }
}
