<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:51
 */

namespace App\SparkPlug\Routing\Exceptions;

use Exception;
use Throwable;

/**
 * Class MissingActionException
 * Wird geworfen wenn Action-Parameter einer Route fehlt
 */
class MissingActionException extends Exception
{
    /**
     * MissingActionException constructor.
     *
     * @param string          $message
     * @param int             $code
     * @param \Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Required Parameter Action is missing', $code, $previous);
    }
}
