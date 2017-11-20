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

return $app;
