<?php $router = app()->make(\App\SparkPlug\Routing\Router::class);

//Router::get('/login', 'UserController@login')->name('login');
$router->get('/', 'PageController@showIndexView')->name('index');
