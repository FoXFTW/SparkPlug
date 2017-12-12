<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 22.11.2017
 * Time: 19:53
 */

namespace App\SparkPlug\Models\Exceptions;

use Exception;

/**
 * Class ModelNotFoundException
 * Thrown if Model with given ID was not found in DB
 *
 * @package App\SparkPlug\Models\Exceptions
 */
class ModelNotFoundException extends Exception
{

}
