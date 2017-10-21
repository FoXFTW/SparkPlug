<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:53
 */

namespace App\SparkPlug\Routing\Exceptions;

use Exception;
use Throwable;

class InvalidActionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Provided Action Parameter does not follow the naming Convention (<Controler>@<Method>)';
        }

        parent::__construct($message, $code, $previous);
    }
}
