<?php

require_once __DIR__."/vendor/autoload.php";

use CoffeeCode\Router\Router;

$router = new Router(URL_BASE);

$router->namespace("MyApp\Controllers");

$router->group(null);
$router->get("/", "Controller:home");

$router->dispatch();