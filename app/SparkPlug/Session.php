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
class Session implements \ArrayAccess
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

    /**
     * Holt Wert aus Session
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function get(string $key)
    {
        if (isset($_SESSION[$this->prefix]['vars'][$key])) {
            return $_SESSION[$this->prefix]['vars'][$key];
        }

        return null;
    }

    /**
     * Setzt Wert in Session
     *
     * @param string $name
     * @param        $value
     */
    public function set(string $name, $value)
    {
        $_SESSION[$this->prefix]['vars'][$name] = $value;
    }

    /**
     * Holt Wert aus Session und löscht Wert
     *
     * @param string $key
     *
     * @return mixed|null
     */
    public function pull(string $key)
    {
        if (!isset($_SESSION[$this->prefix]['vars'][$key])) {
            return null;
        }

        $content = $this->get($key);
        $_SESSION[$this->prefix]['vars'][$key] = null;

        return $content;
    }

    /**
     * Holt Wert aus Session
     *
     * @param string $name Array Key
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
     * @param string $name  Key
     * @param mixed  $value Value
     */
    public function __set($name, $value)
    {
        $_SESSION[$this->prefix][$name] = $value;
    }

    /**
     * Prüft ob Wert in Session vorhanden
     *
     * @param string $name Key
     *
     * @return bool
     */
    public function __isset($name)
    {
        return isset($_SESSION[$this->prefix]['vars'][$name]);
    }

    /**
     * Löscht Wert aus Session
     *
     * @param string $name Key
     */
    public function __unset($name)
    {
        unset($_SESSION[$this->prefix]['vars'][$name]);
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

    /**
     * Whether a offset exists
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($_SESSION[$this->prefix][$offset]);
    }

    /**
     * Offset to retrieve
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($_SESSION[$this->prefix][$offset]) ? $_SESSION[$this->prefix][$offset] : null;
    }

    /**
     * Offset to set
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $_SESSION[$this->prefix][$offset] = $value;
    }

    /**
     * Offset to unset
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if (isset($_SESSION[$this->prefix][$offset])) {
            $_SESSION[$this->prefix][$offset] = null;
        }
    }
}
