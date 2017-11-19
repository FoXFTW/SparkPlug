<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.10.2017
 * Time: 21:57
 */

namespace App\SparkPlug\Routing\Exceptions;

use Exception;

/**
 * Class RouteNotFoundException
 * Wird geworfen, wenn eine gesuchte Route nicht in einer RouteCollection vorhanden ist
 *
 * @package App\SparkPlug\Routing\Exceptions
 */
class RouteNotFoundException extends Exception
{

}
