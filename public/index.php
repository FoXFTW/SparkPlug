<?php use App\SparkPlug\Application, App\SparkPlug\Request\Request, \App\SparkPlug\Exceptions\Handler;

require __DIR__.'/../vendor/autoload.php';

set_exception_handler([Handler::class, "handleException"]);

$app = new Application(realpath(__DIR__.'/../'));

$app->singleton(\App\SparkPlug\Routing\Router::class);

// ToDo load routes elsewhere
require $app->getBasePath().'/routes/web.php';

$response = $app->handle(Request::capture());

$response->send();