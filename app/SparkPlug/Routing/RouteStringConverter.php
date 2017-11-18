<?php declare(strict_types = 1);
/**
 * User: Hannes
 * Date: 23.10.2017
 * Time: 20:33
 */

namespace App\SparkPlug\Routing;

class RouteStringConverter
{
    const CONVERSION_DICTIONARY_FROM = [
        '[a]',
        '[a_]',
        '[0]',
        '[0_]',
        '[?]',
    ];

    const CONVERSION_DICTIONARY_TO = [
        '([a-z]*)',
        '([a-z_]*)',
        '([0-9]*)',
        '([0-9_]*)',
        '([\w]*)',
    ];

    const REGEX_START = '/^';
    const REGEX_END = '$/i';

    public static function toRegex(Route $route): string
    {
        $route = $route->getRoute();
        $route = str_replace('/', '\/', $route);
        $route = str_replace(
            static::CONVERSION_DICTIONARY_FROM,
            static::CONVERSION_DICTIONARY_TO,
            $route
        );

        return static::REGEX_START.$route.static::REGEX_END;
    }
}
