<?php declare(strict_types = 1); use App\SparkPlug\Request\Request, \App\SparkPlug\Exceptions\Handler;

require __DIR__.'/../vendor/autoload.php';

set_exception_handler([Handler::class, "handleException"]);

$app = require __DIR__.'/../config/app.php';

$response = $app->handle(Request::capture());

$response->send();
