<?php
$router->get('/admin/dashboard', 'HomeController@adminDashboard');
$router->get('/client/dashboard', 'HomeController@clientDashboard');

//AUTH
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');
$router->get('/active', 'AuthController@active');
// ADMIN POST 
$router->get('/admin/film/list', 'MoviesController@list');

$router->get('/admin/film/edit', 'MoviesController@showEdit');
$router->post('/admin/film/edit', 'MoviesController@edit');

$router->get('/admin/film/add', 'MoviesController@showAdd');
$router->post('/admin/film/add', 'MoviesController@add');

$router->get('/delete', 'MoviesController@delete');
