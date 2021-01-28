<?php

namespace MyApp\Controllers;

use MyApp\Models\BookModel;

class BookController extends Controller {

    protected function verifyForm()
    {
        if(strlen($_POST['name']) > 50) {
            return 'Campo nome não está no tamanho especificado.';
        }
        if(strlen($_POST['author']) > 50) {
            return 'Campo autor não está no tamanho especificado.';
        }
        if(strlen($_POST['synopsis']) > 2500) {
            return 'Campo de sinopse não está no tamanho especificado.';
        }

        return '';
    }

    protected function loadErrors($page, $error)
    {
        $this->load($page, [
        'form' => [
                'name' => $_POST['name'],
                'author' => $_POST['author'],
                'price' => $_POST['price'],
                'amount' => $_POST['amount'],
                'language' => $_POST['language'],
                'synopsis' => $_POST['synopsis'],
                'genre' => $_POST['genre']
            ],
            'error' => $error
        ]);
    }

    protected function uploadImage()
    {
        if($_FILES['image']['name'] != '') {

            $data = new \DateTime();
            $target_dir = "./uploads/";
            $ext = explode('/', $_FILES['image']['type'])[1];
            $target_file = $target_dir . $data->format('d-m-Y-H-i-s') . '.' . $ext;
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    
            return $target_file;
        } else {
            return false;
        }
    }

    public function home()
    {
        $objs = $this->book_model->findAll();
        $this->load('homeBook', ['objs' => $objs]);
    }

    public function add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load('addBook');
        } else {
            $error = $this->verifyForm();
            if($error == '') {

                $pathToImage = $this->uploadImage();
                $_POST['image'] = $pathToImage;

                $result = $this->book_model->insert();
                if (gettype($result) == 'object') {
                    header('Location: '. strval($_ENV['BASE_URL']) .'/book');
                } else { $this->loadErrors('addBook', $result); }
            } else {
                $this->loadErrors('addBook', $error);
            }

        }
    }

    public function edit($data)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $obj = $this->book_model->findBy('id', $data['id']);
            $this->load("editBook", [
                "obj" => $obj[0]
            ]);
        } else {
            $error = $this->verifyForm();
            if($error == '') {

                $pathtoImage = $this->uploadImage();
                $_POST['image'] = $pathtoImage ? $pathtoImage : $_POST['image'];
    
                $result = $this->book_model->save($data['id']);
                if (gettype($result) == 'object') {
                    header('Location: '. strval($_ENV['BASE_URL']) .'/book');
                } else { $this->loadErrors('editBook', $result); }
            } else {
                $this->loadErrors('editBook', $error);
            }
        }
    }

    public function delete($data)
    {
        $pathToImage = $this->book_model->delete($data['id']);
        unlink($pathToImage);
        header('Location: '. strval($_ENV['BASE_URL']) .'/book');
    }

    public function search($data = null)
    {
        if($data == null) {
            $title = $_GET['query'];
            $objs = $this->book_model->findBy('name', $_GET['query']);
        } else {
            $title = $data['category'];
            if(in_array($title, array('promotions', 'bestSales', 'smallPrice'))) {
                $objs = $this->book_model->findByOrder('amount', 'asc');   
            } else {
                $objs = $this->book_model->findBy('genre', $data['category']);
            }
            if($title == 'promotions') $title = 'melhores promoções';
            if($title == 'bestSales') $title = 'mais vendidos';
            if($title == 'smallPrice') $title = 'menor preço';
        }
        $this->load("home", [
            "title" => $title,
            "query" => !empty($_GET['query']) ? $_GET['query'] : "",
            "objs" => $objs
        ]);
    }

    public function searchAdmin()
    {
        $objs = $this->book_model->findBy('name', $_GET['query']);
        $this->load("homeBook", [
            "query" => $_GET["query"],
            "objs" => $objs
        ]);
    }

    public function details($data) {
        $obj = $this->book_model->findBy('id', $data['id']);
        $this->load("detailsBook", [
            "obj" => $obj[0]
        ]);
    }
}