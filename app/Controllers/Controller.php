<?php

namespace MyApp\Controllers;

class Controller {

    protected function load($view, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(['public', 'templates']);
        $twig = new \Twig\Environment($loader);

        echo $twig->render($view.'.html', $params);
    }

    public function home()
    {
        $this->load("home");
    }

    public function search()
    {
        $this->load("search", ["query" => $_GET["query"]]);
    }
}