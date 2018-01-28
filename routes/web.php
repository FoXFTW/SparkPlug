<?php $router = app()->make(\App\SparkPlug\Routing\Router::class);

$router->get('/', 'PageController@showIndexView')->name('index');
$router->get('/login', 'User\Auth\LoginController@showLoginView')->name('login_view');
$router->post('/login', 'User\Auth\LoginController@login')->name('login');