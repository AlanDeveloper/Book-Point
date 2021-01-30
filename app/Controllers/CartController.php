<?php

namespace MyApp\Controllers;

class CartController extends Controller {

    public function home()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $cart = $this->cart_model->findAll();
            $objs = [];

            for ($i=0; $i < count($cart); $i++) { 
                $obj = $this->book_model->findBy('id', $cart[$i]['id_book']);
                array_push($objs, $obj[0]);
            }
            $this->load("cart", ['objs' => $objs]);
        } else {
            $this->sendToCart();
        }
    }

    public function cancel()
    {
        $this->cart_model->deleteAll();
        header('Location: '. strval($_ENV['BASE_URL']) .'/cart');
    }

    public function delete($data)
    {
        $this->cart_model->delete($data['id']);
        header('Location: '. strval($_ENV['BASE_URL']) .'/cart');
    }

    public function sendToCart($data)
    {
        $this->cart_model->insert($data['id']);
        header('Location: '. strval($_ENV['BASE_URL']) .'/cart');
    }
}
