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
$router->get("/search", "Controller:search");
$router->get("/support", "Controller:support");

$router->get("/login", "UserController:login");
$router->post("/login", "UserController:authLogin");

$router->get("/register", "UserController:register");
$router->post("/register", "UserController:authRegister");

$router->get("/editProfile", "UserController:editProfile");
// $router->post("/editProfile", "UserController:editProfile");

$router->get("/loggout", "UserController:loggout");
$router->get("/deleteProfile", "UserController:deleteProfile");

$router->dispatch();