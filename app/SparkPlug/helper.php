<?php
/**
 * User: Hannes
 * Date: 21.10.2017
 * Time: 16:57
 */

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }
}

if (!function_exists('app')) {
    function app()
    {
        return \App\SparkPlug\Application::getInstance();
    }
}