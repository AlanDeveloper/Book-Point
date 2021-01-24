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

$router->get("/login", "UserController:login");
$router->post("/login", "UserController:authLogin");

$router->get("/register", "UserController:register");
$router->post("/register", "UserController:authRegister");

$router->get("/editProfile", "UserController:editProfile");
$router->post("/editProfile", "UserController:saveProfile");

$router->get("/loggout", "UserController:loggout");
$router->get("/deleteProfile", "UserController:deleteProfile");

$router->get("/administrative", "BookController:administrative");
$router->get("/deleteBook/{id}", "BookController:deleteBook");

$router->get("/addBook", "BookController:addBook");
$router->post("/addBook", "BookController:saveBook");

$router->get("/searchBook", "BookController:searchBook");
$router->get("/category/{category}", "BookController:category");
$router->get("/option/{option}", "BookController:option");

$router->get("/editBook/{id}", "BookController:editBook");
$router->post("/editBook/{id}", "BookController:editSaveBook");

$router->dispatch();