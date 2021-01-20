<?php

require_once __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);

$router->namespace("MyApp\Controllers");

$router->group(null);
$router->get("/", "Controller:home");
$router->get("/search", "Controller:search");
$router->get("/support", "Controller:support");

$router->get("/login", "UserController:login");
$router->post("/login", "UserController:authLogin");

$router->get("/register", "UserController:register");
$router->post("/register", "UserController:authRegister");

$router->dispatch();