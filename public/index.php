<?php

require __DIR__.'/../vendor/autoload.php';

$app = new \App\SparkPlug\Application(realpath(__DIR__.'/../'));

$response = $app->handle(\App\SparkPlug\Request\Request::capture());

$response->send();