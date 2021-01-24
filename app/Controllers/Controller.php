<?php

namespace MyApp\Controllers;

use MyApp\Models\BookModel;
use MyApp\Models\UserModel;

class Controller {

    protected function load($view, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(['public', 'templates']);
        $twig = new \Twig\Environment($loader);
        $twig->addGlobal('session', $_SESSION);

        $protocolo = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
        $url = explode($_ENV['BASE_URL'], "$protocolo://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}");

        $params = array_merge($params, [
            "route" => $url[1],
            "base_url" => $_ENV['BASE_URL']
        ]); 

        echo $twig->render($view.'.html', $params);
    }

    public function __construct()
    {
        $this->book_model = new BookModel();
        $this->user_model = new UserModel();
    }

    public function home()
    {
        $objs = $this->book_model->findByOrderAmount();
        $this->load("home", ['objs' => $objs]);
    }

    public function search()
    {
        $this->load("search", [
            "query" => $_GET["query"]
        ]);
    }
    
    public function support()
    {
        $this->load("support");
    }
}