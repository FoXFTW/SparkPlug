<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:53
 */

namespace App\SparkPlug\Routing\Exceptions;

use Exception;
use Throwable;

/**
 * Class InvalidActionException
 * Wird geworfen wenn Action Parameter einer Route falsch ist
 *
 * @package App\SparkPlug\Routing\Exceptions
 */
class InvalidActionException extends Exception
{
    /**
     * InvalidActionException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Provided Action Parameter does not follow the naming Convention (<Controler>@<Method>)';
        }

        parent::__construct($message, $code, $previous);
    }
}
