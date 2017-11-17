<?php declare(strict_types = 1);
/**
 * Bootstrap the App
 */
$app = new \App\SparkPlug\Application(realpath(__DIR__.'/../'));

$app->singleton(\App\SparkPlug\Routing\Router::class);
$app->loadRoutes();

return $app;
