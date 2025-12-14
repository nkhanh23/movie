<?php
$router->get('/admin/dashboard', 'HomeController@adminDashboard');
$router->get('/', 'HomeController@index');

//AUTH
$router->get('/auth/google/callback', 'AuthController@googleCallback');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');
$router->get('/active', 'AuthController@active');
// ADMIN POST 
$router->get('/admin/film/list', 'MoviesController@list');

$router->get('/watch', 'WatchDetailController@showWatch');
$router->post('/watch', 'WatchDetailController@watch');

$router->get('/admin/film/edit', 'MoviesController@showEdit');
$router->post('/admin/film/edit', 'MoviesController@edit');

$router->get('/admin/film/add', 'MoviesController@showAdd');
$router->post('/admin/film/add', 'MoviesController@add');


$router->get('/admin/film/delete', 'MoviesController@delete');

//ADMIN SEASON
$router->get('/admin/season', 'SeasonController@list');

$router->get('/admin/season/edit', 'SeasonController@showEdit');
$router->post('/admin/season/edit', 'SeasonController@edit');

$router->get('/admin/season/add', 'SeasonController@showAdd');
$router->post('/admin/season/add', 'SeasonController@add');

$router->get('/admin/season/delete', 'SeasonController@delete');

//ADMIN EPISODE
$router->get('/admin/episode', 'EpisodeController@list');

$router->get('/admin/episode/edit', 'EpisodeController@showEdit');
$router->post('/admin/episode/edit', 'EpisodeController@edit');

$router->get('/admin/episode/add', 'EpisodeController@showAdd');
$router->post('/admin/episode/add', 'EpisodeController@add');

$router->get('/admin/episode/delete', 'EpisodeController@delete');

//ADMIN GENRES
$router->get('/admin/genres', 'GenresController@list');

$router->get('/admin/genres/edit', 'GenresController@showEdit');
$router->post('/admin/genres/edit', 'GenresController@edit');

$router->get('/admin/genres/add', 'GenresController@showAdd');
$router->post('/admin/genres/add', 'GenresController@add');

$router->get('/admin/genres/delete', 'GenresController@delete');

//ADMIN USER
$router->get('/admin/user', 'UserController@list');

$router->get('/admin/user/edit', 'UserController@showEdit');
$router->post('/admin/user/edit', 'UserController@edit');

$router->get('/admin/user/add', 'UserController@showAdd');
$router->post('/admin/user/add', 'UserController@add');

$router->get('/admin/user/delete', 'UserController@delete');

//ADMIN ACTOR
$router->get('/admin/person', 'PersonController@list');

$router->get('/admin/person/edit', 'PersonController@showEdit');
$router->post('/admin/person/edit', 'PersonController@edit');

$router->get('/admin/person/add', 'PersonController@showAdd');
$router->post('/admin/person/add', 'PersonController@add');

$router->get('/admin/person/delete', 'PersonController@delete');

//ADMIN ROLE
$router->get('/admin/role', 'RoleController@list');

$router->get('/admin/role/edit', 'RoleController@showEdit');
$router->post('/admin/role/edit', 'RoleController@edit');

$router->get('/admin/role/add', 'RoleController@showAdd');
$router->post('/admin/role/add', 'RoleController@add');

$router->get('/admin/role/delete', 'RoleController@delete');


// -----------------------------------------------------------------
// CLIENT
// -----------------------------------------------------------------

// PAGE DETAIL
$router->get('/detail', 'MovieDetailController@showDetail');
$router->post('/detail', 'MovieDetailController@detail');

$router->get('/api/get-episodes', 'MovieDetailController@getEpisodesApi');

$router->post('/api/post-comment', 'MovieDetailController@postCommentApi');
$router->post('/api/delete-comment', 'MovieDetailController@deleteCommentApi');
$router->post('/api/reply-comment', 'MovieDetailController@replyCommentApi');
$router->post('/api/like-comment', 'MovieDetailController@likeCommentApi');

// PAGE WATCH
$router->get('/watch', 'WatchDetailController@showWatch');
$router->post('/watch', 'WatchDetailController@watch');

// PAGE PERSON
$router->get('/dien_vien', 'PersonDetailController@showPerson');

//PAGE SEARCH
$router->get('/tim_kiem', 'HomeController@search');

//PAGE PHIM LE
$router->get('/phim_le', 'HomeController@phimLe');

//PAGE PHIM BO
$router->get('/phim_bo', 'HomeController@phimBo');

//PAGE PHIM CHIEU RAP
$router->get('/phim_chieu_rap', 'HomeController@phimChieuRap');

//PAGE THE LOAI
$router->get('/the_loai', 'HomeController@theLoai');

//PAGE QUOC GIA
$router->get('/quoc_gia', 'HomeController@quocGia');

//PAGE DIEN VIEN
$router->get('/dien_vien', 'HomeController@dienVien');
