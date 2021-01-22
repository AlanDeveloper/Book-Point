<?php

namespace MyApp\Controllers;

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

    public function home()
    {
        $this->load("home");
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