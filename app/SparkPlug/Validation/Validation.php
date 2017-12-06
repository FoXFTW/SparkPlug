<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 27.11.2017
 * Time: 20:42
 */

namespace App\SparkPlug\Validation;

use App\SparkPlug\Database\DBAccessInterface;
use App\SparkPlug\Request\Request;
use App\SparkPlug\Validation\Exceptions\ValidationException;
use InvalidArgumentException;

class Validation
{
    private const TESTS = [
        'boolean',
        'email',
        'int',
        'float',
        'string',
        'url',
        'unique',
        'username',
        'min',
        'max',
        'confirmed',
    ];


    private $failedRules = [];

    private $data;
    private $currentKey;

    /**
     * Validate Data against Rules
     *
     * @param array $rules
     * @param       $data
     *
     * @return array
     * @throws \App\SparkPlug\Validation\Exceptions\ValidationException
     * @throws \InvalidArgumentException
     */
    public function validate(array $rules, $data): array
    {
        if ($data instanceof Request) {
            $data = $data->all();
        }

        if (!is_array($data)) {
            throw new InvalidArgumentException("The data Attribute should be either an array or an Request object");
        }

        $this->data = $data;

        foreach ($rules as $name => $rule) {
            $this->currentKey = $name;
            if (isset($data[$name])) {
                $validationRules = explode('|', $rule);

                foreach ($validationRules as $validationRule) {
                    $validationRule = explode(':', $validationRule);
                    $rule = array_shift($validationRule);
                    $options = [];
                    if (!empty($validationRule)) {
                        $options = $validationRule;
                    }

                    if (!in_array($rule, static::TESTS)) {
                        throw new InvalidArgumentException("Test {$rule} not in (".implode(static::TESTS)."}");
                    }

                    call_user_func_array([$this, 'test'.ucfirst($rule)], $options);
                }
            } else {
                $this->failedRules[] = "Feld {$name} muss ausgefüllt sein";
            }
        }

        if (!empty($this->failedRules)) {
            throw new ValidationException($this->failedRules);
        }

        return array_intersect_key($data, $rules);
    }

    private function testBoolean()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_BOOLEAN)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist kein Boolean";
        }
    }

    private function testEmail()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_EMAIL)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine EMail Adresse";
        }
    }

    private function testInt()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_INT)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine Ganzzahl";
        }
    }

    private function testFloat()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_FLOAT)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine Gleitkommazahl";
        }
    }

    private function testUrl()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_URL)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine URL";
        }
    }

    private function testUnique(string $table)
    {
        /** @var DBAccessInterface $db */
        $db = app()->make(DBAccessInterface::class);
        /** @var \PDO $db */
        $db = $db->getDB();

        $statement = $db->prepare("SELECT * FROM {$table} WHERE {$this->currentKey} = ?");
        $statement->execute([$this->data[$this->currentKey]]);

        if (!empty($statement->fetchAll())) {
            $this->failedRules[] = "{$this->currentKey} bereits vergeben";
        }
    }

    private function testUsername()
    {
        if (!filter_var(
            $this->data[$this->currentKey],
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^[\w-_]+$/"]]
        )) {
            $this->failedRules[] = "Benutername darf nur Alphanumerische Zeichen sowie -_ enthalten";
        }
    }

    private function testMin($min)
    {
        if (is_numeric($this->data[$this->currentKey])) {
            $pass = intval($this->data[$this->currentKey]) >= intval($min);
            $text = "größer als {$min} sein";
        } else {
            $pass = strlen($this->data[$this->currentKey]) >= intval($min);
            $text = "mehr als {$min} Zeichen enthalten";
        }

        if ($pass === false) {
            $this->failedRules[] = "Feld {$this->currentKey} muss {$text}";
        }
    }

    private function testMax($max)
    {
        if (is_numeric($this->data[$this->currentKey])) {
            $pass = intval($this->data[$this->currentKey]) <= intval($max);
            $text = "kleiner als {$max} sein";
        } else {
            $pass = strlen($this->data[$this->currentKey]) <= intval($max);
            $text = "weniger als {$max} Zeichen enthalten";
        }

        if ($pass === false) {
            $this->failedRules[] = "Feld {$this->currentKey} muss {$text}";
        }
    }

    private function testConfirmed()
    {
        if (!isset($this->data["{$this->currentKey}_confirmation"])) {
            $this->failedRules[] = "Feld {$this->currentKey} muss bestätigt werden";
        }

        if ($this->data["{$this->currentKey}_confirmation"] !== $this->data[$this->currentKey]) {
            $this->failedRules[] = "Feld {$this->currentKey} stimmt nicht mit Bestätigung überein";
        }
    }
}
