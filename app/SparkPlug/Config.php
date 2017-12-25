<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 20.11.2017
 * Time: 18:37
 */

namespace App\SparkPlug;

/**
 * Class Config
 * Lädt PHP Dateien aus config Ordner
 */
class Config
{
    private $configFolder;
    private $config = [];

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->configFolder = app()->getBasePath().'/config/';

        $files = glob($this->configFolder.'*.php');

        foreach ($files as $file) {
            $key = str_replace(['.php', $this->configFolder], '', $file);
            $this->config[$key] = require $file;
        }
    }

    /**
     * @param string $key Der zu suchende Key in Dot notaion
     *
     * @return array|mixed
     */
    public function get(string $key)
    {
        $config = $this->config;
        $keys = explode('.', $key);

        foreach ($keys as $key) {
            if (!is_array($config) || !array_key_exists($key, $config)) {
                throw new \InvalidArgumentException("No config with key {$key} found!");
            }
            $config = &$config[$key];
        }

        return $config;
    }
}
