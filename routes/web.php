<?php $router = app()->make(\App\SparkPlug\Routing\Router::class);

//Router::get('/login', 'UserController@login')->name('login');
$router->get('/', 'PageController@showIndexView')->name('index');
$router->get('/login', 'User\AccountController@showLoginView')->name('login');
$router->get('/register', 'User\AccountController@showRegisterView')->name('register');
