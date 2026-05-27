<?php
// Загрузка автозагрузчика Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Загрузка переменных окружения
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Старт сессии
session_start();

// Инициализация маршрутизатора
use Core\Router;
use Core\Request;

$router = new Router();

// Определение маршрутов (будет дополняться)
$router->get('/', 'HomeController@index');
$router->get('/auth', 'AuthController@showLogin');
$router->post('/auth', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

$router->get('/profile', 'ProfileController@index', ['auth']);
$router->get('/application/create', 'ApplicationController@create', ['auth']);
$router->get('/application/view', 'ApplicationController@view', ['auth']);
$router->post('/application/store', 'ApplicationController@store', ['auth']);
$router->post('/application/comment', 'ApplicationController@addComment', ['auth']);


$router->get('/admin', 'AdminController@index', ['auth', 'admin']);
$router->get('/admin/view', 'AdminController@viewApplication', ['auth', 'admin']);
$router->post('/admin/assign', 'AdminController@assign', ['auth', 'admin']);
$router->post('/admin/update-status', 'AdminController@updateStatus', ['auth', 'admin']);

// Управление пользователями (только админ)
$router->get('/admin/users', 'AdminController@users', ['auth', 'admin']);
$router->get('/admin/users/create', 'AdminController@createUser', ['auth', 'admin']);
$router->post('/admin/users/store', 'AdminController@storeUser', ['auth', 'admin']);
$router->get('/admin/users/edit', 'AdminController@editUser', ['auth', 'admin']);
$router->post('/admin/users/update', 'AdminController@updateUser', ['auth', 'admin']);
$router->post('/admin/users/delete', 'AdminController@deleteUser', ['auth', 'admin']);

// Редактирование профиля
$router->get('/profile/edit', 'ProfileController@edit', ['auth']);
$router->post('/profile/update', 'ProfileController@update', ['auth']);
$router->post('/profile/upload-avatar', 'ProfileController@uploadAvatar', ['auth']);


// Запуск обработки запроса
$router->dispatch(Request::uri(), Request::method());