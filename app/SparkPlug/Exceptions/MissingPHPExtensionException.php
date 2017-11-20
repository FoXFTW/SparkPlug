<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.11.2017
 * Time: 23:36
 */

namespace App\SparkPlug\Exceptions;

use Exception;

/**
 * Class MissingExtensionException
 * Thrown if a PHP Extension is required but not loaded
 *
 * @package App\SparkPlug\Exceptions
 */
class MissingPHPExtensionException extends Exception
{

}
