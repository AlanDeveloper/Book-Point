<?php

require_once __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;


// START SESSION
session_start();

// LOAD ENV
if(file_exists(".env")) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// ROUTES
$base_url = strval($_ENV['BASE_URL']);
$router = new Router($base_url);

$router->namespace("MyApp\Controllers");

$router->group(null);
$router->get("/", "Controller:home");
$router->get("/search", "BookController:search");


$router->get("/support", "Controller:support");
$router->post("/support", "Controller:support");

// CART
$router->group('cart');

$router->get("/", "CartController:home");

$router->get("/sendToCart/{id}", "CartController:sendToCart");

$router->get("/delete/{id}", "CartController:delete");

$router->get("/cancel", "CartController:cancel");

// USER
$router->group('user');

$router->get("/add", "UserController:add");
$router->post("/add", "UserController:add");

$router->get("/login", "UserController:auth");
$router->post("/login", "UserController:auth");

$router->get("/edit/{id}", "UserController:edit");
$router->post("/edit/{id}", "UserController:edit");

$router->get("/loggout", "UserController:loggout");

$router->get("/delete", "UserController:delete");

// BOOK
$router->group('book');

$router->get("/", "BookController:home");

$router->get("/{id}", "BookController:details");

$router->get("/add", "BookController:add");
$router->post("/add", "BookController:add");

$router->get("/edit/{id}", "BookController:edit");
$router->post("/edit/{id}", "BookController:edit");

$router->get("/search", "BookController:search");
$router->get("/search/{category}", "BookController:search");
$router->get("/searchAdmin", "BookController:searchAdmin");

$router->get("/delete/{id}", "BookController:delete");

$router->dispatch();