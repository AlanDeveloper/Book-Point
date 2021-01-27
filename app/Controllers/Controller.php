<?php

namespace MyApp\Controllers;

use MyApp\Controllers\Support\Email;

use MyApp\Models\BookModel;
use MyApp\Models\UserModel;
use MyApp\Models\Cart;

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
        $this->cart = new Cart();
    }

    public function home()
    {
        $objs = $this->book_model->findByOrder('amount', 'asc');
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
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load("support");
        } else {
            $mail = new Email();
            $mail->add(
                'Equipe BookPoint',
                '<h1>Feedback de usuário</h1>Agradecemos pelo seu tempo em tentar fazer-nos uma plataforma melhor, retornaremos seu problema/sugestão em brebe :)',
                explode('@', $_POST['email'])[0],
                $_POST['email']
            )->send();

            header('Location: '. strval($_ENV['BASE_URL']) .'/support');
        }
    }

    public function car()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $cart = $this->cart->findAll();
            $objs = [];
            foreach($cart as $obj) {
                $objs = $this->book_model->findBy('id', $obj);
                array_push($objs, $obj);
            }
            $this->load("cart", ['objs' => $objs]);
        } else {
            $this->sendToCart();
        }
    }

    public function sendToCart()
    {
        echo 'post';
    }
}