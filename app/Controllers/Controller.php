<?php

namespace MyApp\Controllers;

class Controller {

    protected function load($view, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(['public', 'templates']);
        $twig = new \Twig\Environment($loader);

        $params = array_merge($params, [
            "route" => explode("?", $_SERVER["REQUEST_URI"])[0],
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