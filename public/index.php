<?php declare(strict_types = 1); use App\SparkPlug\Request\Request, \App\SparkPlug\Exceptions\Handler;

require __DIR__.'/../vendor/autoload.php';

set_exception_handler([Handler::class, "handleException"]);

/** @var \App\SparkPlug\Application $app */
$app = require __DIR__.'/../bootstrap/app.php';

$response = $app->handle(Request::capture());

$response->send();
