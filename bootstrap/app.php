<?php declare(strict_types = 1);
/**
 * Bootstrap the App
 */
$app = new \App\SparkPlug\Application(realpath(__DIR__.'/../'));

/**
 * Declare Singletons
 */
$app->singleton(\App\SparkPlug\Routing\Router::class);
$app->singleton(\App\SparkPlug\Config::class);

/**
 * Load Routes
 */
$app->loadRoutes();

/**
 * Set Default Timezone based on config
 */
if (!date_default_timezone_set(config('app.timezone'))) {
    // Fallback if Config is invalid
    date_default_timezone_set(DateTimeZone::listIdentifiers(DateTimeZone::UTC)[0]);
}

return $app;
