<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:51
 */

namespace App\SparkPlug\Routing\Exceptions;

use Exception;
use Throwable;

class MissingActionException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct('Required Parameter Action is missing', $code, $previous);
    }
}
