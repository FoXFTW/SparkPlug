<?php

require __DIR__.'/../vendor/autoload.php';

$app = new \App\LaunchPad\Application(realpath(__DIR__.'/../'));

$response = $app->handle(\App\LaunchPad\Request\Request::capture());

$response->send();