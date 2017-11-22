<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:57
 */

if (!function_exists('str_contains')) {
    /**
     * Wrapper für Substring Suchen
     *
     * @param string $haystack String
     * @param string $needle   Substring
     *
     * @return bool
     */
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('app')) {
    /**
     * Gibt Instanz der App zurück
     *
     * @return \App\SparkPlug\Application
     */
    function app()
    {
        return \App\SparkPlug\Application::getInstance();
    }
}

if (!function_exists('config')) {
    /**
     * Durchsucht Config nach gegebenem Key
     *
     * @param string $key
     *
     * @return string|array|bool|mixed
     */
    function config(string $key)
    {
        /** @var \App\SparkPlug\Config $config */
        $config = app()->make(\App\SparkPlug\Config::class);

        return $config->get($key);
    }
}

if (!function_exists('base_path')) {
    /**
     * Gibt angegebenen Pfad mit Basispfad zurück
     *
     * @param string $path
     *
     * @return string
     */
    function base_path(string $path)
    {
        return app()->getBasePath()."/{$path}";
    }
}

if (!function_exists('database_path')) {
    /**
     * Gibt angegebenen Pfad mit Basispfad zurück
     *
     * @param string $path
     *
     * @return string
     */
    function database_path(string $path)
    {
        return app()->getBasePath()."/storage/database/{$path}";
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $path
     *
     * @return \App\SparkPlug\Response\Redirect
     */
    function redirect(string $path)
    {
        return app()->makeWith(\App\SparkPlug\Response\Redirect::class, [$path])->send();
    }
}

if (!function_exists('back')) {
    function back()
    {
        return app()->makeWith(\App\SparkPlug\Response\Redirect::class, [session_get('previous_page')])->send();
    }
}

if (!function_exists('bcrypt')) {
    /**
     * @param string $string String to hash
     *
     * @return bool|string
     */
    function bcrypt(string $string)
    {
        return password_hash($string, PASSWORD_BCRYPT);
    }
}

if (!function_exists('old')) {
    /**
     * Holt alten Request-Input aus Session
     *
     * @param string $key
     *
     * @return mixed|null|void
     */
    function old(string $key)
    {
        /** @var \App\SparkPlug\Session $session */
        $session = app()->make(\App\SparkPlug\Session::class);

        return $session->pull($key);
    }
}

if (!function_exists('login_check')) {
    /**
     * Check ob Nutzer eingeloggt
     *
     * @return bool
     */
    function login_check(): bool
    {
        $auth = app()->make(\App\SparkPlug\Auth\Auth::class);

        return $auth->check();
    }
}

if (!function_exists('session_set')) {
    function session_set(string $name, $value)
    {
        $session = app()->make(\App\SparkPlug\Session::class);
        $session->set($name, $value);
    }
}

if (!function_exists('session_get')) {
    function session_get(string $name)
    {
        $session = app()->make(\App\SparkPlug\Session::class);

        return $session->get($name);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        $token = session_get('csrf_token');
        if (is_null($token)) {
            session_set('csrf_token', bin2hex(random_bytes(32)));
        }

        return session_get('csrf_token');
    }
}