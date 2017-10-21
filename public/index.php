<?php use App\SparkPlug\Application, App\SparkPlug\Request\Request;

require __DIR__.'/../vendor/autoload.php';

$app = new Application(realpath(__DIR__.'/../'));

$response = $app->handle(Request::capture());

$response->send();