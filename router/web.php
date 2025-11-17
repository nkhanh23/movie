<?php
$router->get('admin/dasboard', 'HomeController@adminDashboard');
$router->get('clients/dasboard', 'HomeControllerController@clientDashboard');

$router->get('login', 'AuthController@index');
$router->post('login', 'AuthControllerController@index');
