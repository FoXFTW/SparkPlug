<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 27.11.2017
 * Time: 20:41
 */

namespace App\SparkPlug\Validation\Exceptions;

use Exception;

class ValidationException extends Exception
{
    private $errors = [];

    public function __construct(array $failedRules)
    {
        $this->message = implode('<br>', $failedRules);
        $this->errors = $failedRules;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
