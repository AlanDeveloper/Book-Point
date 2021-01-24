<?php

namespace MyApp\Controllers;

use MyApp\Models\BookModel;

class BookController extends Controller {

    protected $book_model;

    public function __construct()
    {
        $this->book_model = new BookModel();
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

    protected function upload_image()
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

    public function administrative()
    {
        $objs = $this->book_model->findAll();
        $this->load('administrative', ['objs' => $objs]);
    }

    public function addBook()
    {
        $this->load('addBook');
    }

    public function saveBook()
    {
        $pathImage = $this->upload_image();
        $_POST['image'] = $pathImage;

        $result = $this->book_model->insert();
        if (gettype($result) == 'object') {
            header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
        } else { $this->loadErrors('addBook', $result); }
    }

    public function editBook($data)
    {
        $obj = $this->book_model->findBy('id', $data['id']);
        $this->load("editBook", [
            "obj" => $obj[0]
        ]);
    }

    public function editSaveBook($data)
    {
        $pathImage = $this->upload_image();
        $_POST['image'] = $pathImage ? $pathImage : $_POST['image'];

        $result = $this->book_model->save($data['id']);
        if (gettype($result) == 'object') {
            // header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
        } else { $this->loadErrors('editBook', $result); }
    }

    public function search()
    {
        $objs = $this->book_model->findBy('name', $_GET['query']);
        $this->load("search", [
            "query" => $_GET["query"],
            "objs" => $objs
        ]);
    }

    public function searchBook()
    {
        $objs = $this->book_model->findBy('name', $_GET['query']);
        $this->load("administrative", [
            "query" => $_GET["query"],
            "objs" => $objs
        ]);
    }

    public function category($data)
    {
        $objs = $this->book_model->findBy('genre', $data['category']);
        // $_GET['query'] = $data['category'];
        $this->load("search", ["objs" => $objs]);
    }

    public function option()
    {
        $objs = $this->book_model->findByOrder('amount', 'asc');
        $this->load("search", ["objs" => $objs]);
    }

    public function deleteBook($data)
    {
        $pathToImage = $this->book_model->delete($data['id']);
        unlink($pathToImage);
        header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
    }
}