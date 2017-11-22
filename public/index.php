<?php declare(strict_types = 1);

require __DIR__.'/../vendor/autoload.php';

set_exception_handler([\App\SparkPlug\Exceptions\Handler::class, "handleException"]);

/** @var \App\SparkPlug\Application $app */
$app = require __DIR__.'/../bootstrap/app.php';

$response = $app->handle(\App\SparkPlug\Request\Request::capture());

$response->send();
