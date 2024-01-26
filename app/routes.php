<?php


use App\Router;

require_once __DIR__ . '/controllers/HomeController.php';

// Create an instance of the Router
$router = new Router();

// Define routes
$router->addRoute('/', 'HomeController@index');
$router->addRoute('/login', 'AuthController@login');
$router->addRoute('/register', 'AuthController@register');
$router->addRoute('/logout', 'AuthController@logout');

$router->addRoute('/listing', 'HomeController@listing');
$router->addRoute('/listings', 'HomeController@index');
$router->addRoute('/profile', 'UserController@profile');
$router->addRoute('/admin', 'AdminController@index');
$router->addRoute('/block-user', 'AdminController@blockUser');
$router->addRoute('/activate-user', 'AdminController@activateUser');
$router->addRoute('/category-listings', 'HomeController@category');
$router->addRoute('/my-profile', 'UserController@myProfile');
$router->addRoute('/update-profile', 'UserController@updateProfile');


$router->addRoute('/about', 'HomeController@index');
$router->addRoute('/contact', 'ContactController@index');

$router->addRoute('/chats', 'ChatController@chats');
$router->addRoute('/send-message', 'ChatController@sendMessage');


$router->addRoute('/my-listings', 'PostController@myListings');
$router->addRoute('/create-post', 'PostController@create');
$router->addRoute('/store-post', 'PostController@store');
$router->addRoute('/edit-post', 'PostController@edit');



$router->addRoute('/update-post', 'PostController@update');

$router->addRoute('/delete-post', 'PostController@delete');


// Run the router
$router->route($_SERVER['REQUEST_URI']);
