<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 04.12.2017
 * Time: 20:29
 */

namespace App\SparkPlug\Exceptions;

use Exception;

/**
 * Class TokenMissMatchException
 * Thrown if CSRF Tokens do not match
 */
class TokenMissMatchException extends Exception
{

}
