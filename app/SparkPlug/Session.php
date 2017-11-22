<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 27.11.2017
 * Time: 20:08
 */

namespace App\SparkPlug;

/**
 * Class Session
 * Wrapper für $_SESSION
 *
 * @package App\SparkPlug
 */
class Session
{
    private $started = false;
    private $prefix;

    /**
     * Session constructor.
     * Erstellt Session und holt Prefix aus config
     */
    public function __construct()
    {
        $this->prefix = config('app.session_prefix');

        if (session_status() !== PHP_SESSION_ACTIVE) {
            $this->started = session_start();
        }
        session_regenerate_id(true);
    }

    public function get(string $key)
    {
        return $this->__get($key);
    }

    public function set(string $name, $value)
    {
        $this->__set($name, $value);
    }

    public function pull(string $key)
    {
        if (!$this->__isset($key)) {
            return;
        }

        $content = $this->get($key);
        $this->__unset($key);

        return $content;
    }

    /**
     * Holt Wert aus Session
     *
     * @param $name string Array Key
     *
     * @return null|mixed
     */
    public function __get($name)
    {
        if (isset($_SESSION[$this->prefix][$name])) {
            return $_SESSION[$this->prefix][$name];
        }

        return null;
    }

    /**
     * Setzt Wert in Session
     *
     * @param $name  string Key
     * @param $value mixed Value
     */
    public function __set($name, $value)
    {
        $_SESSION[$this->prefix][$name] = $value;
    }

    /**
     * Prüft ob Wert in Session vorhanden
     *
     * @param $name string Key
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($_SESSION[$this->prefix][$name]);
    }

    /**
     * Löscht Wert aus Session
     *
     * @param $name string Key
     */
    public function __unset($name)
    {
        unset($_SESSION[$this->prefix][$name]);
    }

    /**
     * Zerstört Session
     *
     * @return bool
     */
    public function destroy()
    {
        if ($this->started) {
            $this->started = !session_destroy();
            unset($_SESSION);

            return !$this->started;
        }

        return false;
    }
}
