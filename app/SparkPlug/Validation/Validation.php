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

/**
 * Class Validation
 * Validiert Eingaben
 *
 * @package App\SparkPlug\Validation
 */
class Validation
{
    private const TESTS = [
        'alpha',
        'alpha_num',
        'alpha_dash',
        'boolean',
        'email',
        'exists',
        'int',
        'float',
        'string',
        'url',
        'unique',
        'username',
        'min',
        'max',
        'confirmed',
        'nullable',
        'file',
    ];

    private const EXCLUDE_TESTS = [
        'nullable',
    ];

    private $failedRules = [];

    private $data;
    private $currentKey;

    /**
     * Validate Data against Rules
     *
     * @param array $rules
     * @param mixed $data
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

            $validationRules = explode('|', $rule);

            if (!isset($data[$name]) && !isset($validationRules['nullable'])) {
                $this->failedRules[] = "Feld {$name} muss ausgefüllt sein";
            } else {
                foreach ($validationRules as $validationRule) {
                    $validationRule = explode(':', $validationRule);

                    $rule = array_shift($validationRule);
                    $options = [];
                    if (!empty($validationRule)) {
                        $options = explode(',', $validationRule[0]);
                    }

                    if (!in_array($rule, static::TESTS)) {
                        throw new InvalidArgumentException("Test {$rule} not in (".implode(', ', static::TESTS).")");
                    }

                    if (!in_array($rule, static::EXCLUDE_TESTS)) {
                        if (!call_user_func_array(
                            [$this, 'test'.str_replace('_', '', ucwords($rule, '_'))],
                            $options
                        )) {
                            // Flash data into session on fail
                            session_set($name, $data[$name]);
                        }
                    }
                }
            }

        }

        if (!empty($this->failedRules)) {
            throw new ValidationException($this->failedRules);
        }

        return array_intersect_key($data, $rules);
    }

    /**
     * Test ob Data ein String ist
     *
     * @return bool
     */
    private function testString()
    {
        if (!is_string($this->data[$this->currentKey]) || strip_tags(
                $this->data[$this->currentKey]
            ) !== $this->data[$this->currentKey]) {
            $this->failedRules[] = "Feld {$this->currentKey} ist kein String";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data ein Boolean ist
     *
     * @return bool
     */
    private function testBoolean()
    {
        if (is_null(filter_var($this->data[$this->currentKey], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))) {
            $this->failedRules[] = "Feld {$this->currentKey} ist kein Boolean";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data eine Email ist
     *
     * @return bool
     */
    private function testEmail()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_EMAIL)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine EMail Adresse";

            return false;
        }

        return true;
    }

    /**
     * Test of Data ein Int ist
     *
     * @return bool
     */
    private function testInt()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_INT)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine Ganzzahl";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data ein Float ist
     *
     * @return bool
     */

    private function testFloat()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_FLOAT)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine Gleitkommazahl";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data eine URL ist
     *
     * @return bool
     */
    private function testUrl()
    {
        if (!filter_var($this->data[$this->currentKey], FILTER_VALIDATE_URL)) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine URL";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data in angegebener Tabelle nicht existiert
     *
     * @param string      $table
     *
     * @param null|string $attribute
     *
     * @return bool
     */
    private function testUnique(string $table, ?string $attribute = null)
    {
        /** @var DBAccessInterface $db */
        $db = app()->make(DBAccessInterface::class);
        /** @var \PDO $db */
        $db = $db->getDB();

        $key = $this->currentKey;
        if (!is_null($attribute)) {
            $key = $attribute;
        }

        $statement = $db->prepare("SELECT * FROM {$table} WHERE {$key} LIKE ?");
        $statement->execute([$this->data[$this->currentKey]]);

        if (!empty($statement->fetchAll())) {
            $this->failedRules[] = "{$this->currentKey} bereits vergeben";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data in angegebener Tabelle nicht existiert
     *
     * @param string      $table
     *
     * @param null|string $attribute
     *
     * @return bool
     */
    private function testExists(string $table, ?string $attribute = null)
    {
        /** @var DBAccessInterface $db */
        $db = app()->make(DBAccessInterface::class);
        /** @var \PDO $db */
        $db = $db->getDB();

        $key = $this->currentKey;
        if (!is_null($attribute)) {
            $key = $attribute;
        }

        $statement = $db->prepare("SELECT * FROM {$table} WHERE {$key} LIKE ?");
        $statement->execute([$this->data[$this->currentKey]]);

        if (empty($statement->fetchAll())) {
            $this->failedRules[] = "{$this->currentKey} existiert nicht";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data ein valider Benutzername ist
     *
     * @return bool
     */
    private function testUsername()
    {
        if (!filter_var(
            $this->data[$this->currentKey],
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^[\w-_]+$/"]]
        )) {
            $this->failedRules[] = "Benutername darf nur Alphanumerische Zeichen sowie -_ enthalten";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data mindestens x groß ist
     *
     * @param $min
     *
     * @return bool
     */
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

            return false;
        }

        return true;
    }

    /**
     * Test ob data max x groß ist
     *
     * @param $max
     *
     * @return bool
     */
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

            return false;
        }

        return true;
    }

    /**
     * Test ob Bestätigung in Data vorhanden ist
     *
     * @return bool
     */
    private function testConfirmed()
    {
        if (!isset($this->data["{$this->currentKey}_confirmation"])) {
            $this->failedRules[] = "Feld {$this->currentKey} muss bestätigt werden";

            return false;
        }

        if ($this->data["{$this->currentKey}_confirmation"] !== $this->data[$this->currentKey]) {
            $this->failedRules[] = "Feld {$this->currentKey} stimmt nicht mit Bestätigung überein";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data aus Buchstaben sowie -_ besteht
     *
     * @return bool
     */
    private function testAlphaDash()
    {
        if (!filter_var(
            $this->data[$this->currentKey],
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^[a-zA-Z-_]+$/"]]
        )) {
            $this->failedRules[] = "Feld {$this->currentKey} darf nur Alphabetische Zeichen sowie -_ enthalten";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data nur aus Buchstaben besteht
     *
     * @return bool
     */
    private function testAlpha()
    {
        if (!filter_var(
            $this->data[$this->currentKey],
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^[a-zA-Z]+$/"]]
        )) {
            $this->failedRules[] = "Feld {$this->currentKey} darf nur Alphabetische Zeichen enthalten";

            return false;
        }

        return true;
    }

    /**
     * Test ob Data aus Buchstaben und Zahlen besteht
     *
     * @return bool
     */
    private function testAlphaNum()
    {
        if (!filter_var(
            $this->data[$this->currentKey],
            FILTER_VALIDATE_REGEXP,
            ["options" => ["regexp" => "/^[a-zA-Z0-9]+$/"]]
        )) {
            $this->failedRules[] = "Feld {$this->currentKey} darf nur Alphanumerische Zeichen enthalten";

            return false;
        }

        return true;
    }

    private function testFile()
    {
        if (!is_array($this->data[$this->currentKey])) {
            return false;
        }

        $file = $this->data[$this->currentKey];

        if (!file_exists($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $this->failedRules[] = "Feld {$this->currentKey} ist keine Datei";

            return false;
        }

        if ($file['size'] > $this->maxFileUploadInBytes()) {
            $this->failedRules[] = "Datei {$this->currentKey} ist Übersteig Upload-Limit. Is: {$file['size']} Max: {$this->maxFileUploadInBytes()}";

            return false;
        }

        return true;
    }

    /**
     * @param $val
     *
     * @return int|string
     */
    private function returnBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = intval($val);

        switch (substr($last, -1)) {
            case 'M':
            case 'm':
                return (int) $val * 1048576;
            case 'K':
            case 'k':
                return (int) $val * 1024;
            case 'G':
            case 'g':
                return (int) $val * 1073741824;
            default:
                return $val;
        }
    }

    /**
     * @return mixed
     */
    private function maxFileUploadInBytes()
    {
        $maxUpload = $this->returnBytes(ini_get('upload_max_filesize'));
        $maxPost = $this->returnBytes(ini_get('post_max_size'));
        $memoryLimit = $this->returnBytes(ini_get('memory_limit'));

        return min($maxUpload, $maxPost, $memoryLimit);
    }
}
