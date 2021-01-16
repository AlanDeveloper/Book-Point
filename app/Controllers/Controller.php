<?php

namespace MyApp\Controllers;

class Controller {

    protected function load($view, $params = [])
    {
        $loader = new \Twig\Loader\FilesystemLoader(['public', 'templates']);
        $twig = new \Twig\Environment($loader);

        echo $twig->render($view.'.html.twig', $params);
    }

    public function home()
    {
        $this->load("home", ["name" => "Alan"]);
    }
}