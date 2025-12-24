<?php
$router->get('/admin/dashboard', 'HomeController@adminDashboard');
$router->get('/', 'HomeController@index');

//AUTH
$router->get('/auth/google/callback', 'AuthController@googleCallback');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->post('/register', 'AuthController@register');
$router->get('/active', 'AuthController@active');
$router->get('/forgot', 'AuthController@showForgot');
$router->post('/forgot', 'AuthController@forgot');
$router->get('/reset', 'AuthController@showReset');
$router->post('/reset', 'AuthController@reset');
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

//ADMIN SOURCE
$router->get('/admin/source', 'SourceController@list');

$router->get('/admin/source/edit', 'SourceController@showEdit');
$router->post('/admin/source/edit', 'SourceController@edit');

$router->get('/admin/source/add', 'SourceController@showAdd');
$router->post('/admin/source/add', 'SourceController@add');

$router->get('/admin/source/delete', 'SourceController@delete');

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

//ADMIN COMMENT
$router->get('/admin/comments', 'CommentController@list');
$router->get('/admin/comments/delete', 'CommentController@delete');

//ADMIN LOG
$router->get('/admin/logs', 'ActivityController@list');
$router->get('/admin/logs/delete', 'ActivityController@delete');


//ADMIN CRAWLER
$router->get('/admin/crawler', 'CrawlerController@list');
$router->get('/admin/crawler/sync-api', 'CrawlerController@syncApi');

//ADMIN SUPPORT
$router->get('/admin/support', 'SupportController@list');

$router->get('/admin/support/reply', 'SupportController@showReply');
$router->post('/admin/support/reply', 'SupportController@reply');


$router->get('/admin/support/delete', 'SupportController@delete');

//ADMIN SUPPORT TYPE
$router->get('/admin/support_type', 'SupportTypeController@list');

$router->get('/admin/support_type/edit', 'SupportTypeController@showEdit');
$router->post('/admin/support_type/edit', 'SupportTypeController@edit');

$router->get('/admin/support_type/add', 'SupportTypeController@showAdd');
$router->post('/admin/support_type/add', 'SupportTypeController@add');

$router->get('/admin/support_type/delete', 'SupportTypeController@delete');

//ADMIN COUNTRY
$router->get('/admin/country', 'CountryController@list');

$router->get('/admin/country/edit', 'CountryController@showEdit');
$router->post('/admin/country/edit', 'CountryController@edit');

$router->get('/admin/country/add', 'CountryController@showAdd');
$router->post('/admin/country/add', 'CountryController@add');

$router->get('/admin/country/delete', 'CountryController@delete');

//ADMIN SETTING
$router->get('/admin/settings', 'SettingController@showGeneral');
$router->post('/admin/settings/general', 'SettingController@updateGeneral');
$router->post('/admin/settings/email', 'SettingController@updateEmail');



// -----------------------------------------------------------------
// CLIENT
// -----------------------------------------------------------------

// PAGE DETAIL
$router->get('/detail', 'MovieDetailController@showDetail');
$router->post('/detail', 'MovieDetailController@detail');

$router->get('/api/get-episodes', 'MovieDetailController@getEpisodesApi');

$router->post('/api/post-comment', 'CommentUserController@postCommentApi');
$router->post('/api/delete-comment', 'CommentUserController@deleteCommentApi');
$router->post('/api/reply-comment', 'CommentUserController@replyCommentApi');
$router->post('/api/like-comment', 'CommentUserController@likeCommentApi');

// PAGE WATCH
$router->get('/watch', 'WatchDetailController@showWatch');
$router->post('/watch', 'WatchDetailController@watch');
$router->post('/api/save-history', 'WatchDetailController@saveHistory');

// PAGE PERSON
$router->get('/dien_vien/chi_tiet', 'PersonDetailController@showPerson');

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

//PAGE LIEN HE
$router->get('/lien_he', 'AccountController@showContact');
$router->post('/lien_he', 'AccountController@contact');

//PAGE GIOI THIEU
$router->get('/gioi_thieu', 'AccountController@showIntroduce');

//PAGE THONG BAO
$router->get('/thong_bao', 'AccountController@showNotice');

//PAGE TAI KHOAN
$router->get('/tai_khoan', 'AccountController@showAccount');

//PAGE YEU THICH
$router->get('/yeu_thich', 'AccountController@showFavorite');
$router->post('/api/toggle-favorite', 'AccountController@toggleFavoriteApi');

//PAGE EDIT
$router->get('/tai_khoan/chinh_sua', 'AccountController@showEdit');
$router->post('/tai_khoan/chinh_sua', 'AccountController@edit');
$router->get('/tai_khoan/bao_mat', 'AccountController@showSecurity');
$router->post('/tai_khoan/bao_mat', 'AccountController@security');

//PAGE XEM TIEP
$router->get('/xem_tiep', 'AccountController@showNextWatch');
$router->get('/delete-history-dashboard', 'AccountController@deleteHistoryDashboard');
$router->get('/delete-history-continue-page', 'AccountController@deleteHistoryContinuePage');
