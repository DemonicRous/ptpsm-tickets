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
$router->post('/application/store', 'ApplicationController@store', ['auth']);

$router->get('/admin', 'AdminController@index', ['auth', 'admin']);
$router->post('/admin/update-status', 'AdminController@updateStatus', ['auth', 'admin']);

// Запуск обработки запроса
$router->dispatch(Request::uri(), Request::method());