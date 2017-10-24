<?php use App\SparkPlug\Application, App\SparkPlug\Request\Request;

require __DIR__.'/../vendor/autoload.php';

set_exception_handler([\App\SparkPlug\Exceptions\Handler::class, "handleException"]);

$app = new Application(realpath(__DIR__.'/../'));

$app->singleton(\App\SparkPlug\Routing\Router::class);

$response = $app->handle(Request::capture());

$response->send();