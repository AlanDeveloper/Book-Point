<?php

namespace MyApp\Controllers;

class CartController extends Controller {

    public function home()
    {
        $total = 0;
        $objs = [];
        if(!empty($_SESSION['logged'])) {
            $cart = $this->cart_model->findAll();

            for ($i=0; $i < count($cart); $i++) { 
                $obj = $this->book_model->findBy('id', $cart[$i]['id_book']);
                array_push($objs, $obj[0]);
                $total += intval($obj[0]->getPrice());
            }
        }

        $total = number_format($total, 2, ",", ".");
        $this->load("cart", ['objs' => $objs, 'total' => $total]);
    
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
        $found =$this->cart_model->find($data['id']);
        if(!$found) {
            $this->cart_model->insert($data['id']);
        }
        header('Location: '. strval($_ENV['BASE_URL']) .'/cart');
    }
}
