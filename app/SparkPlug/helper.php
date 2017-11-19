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
