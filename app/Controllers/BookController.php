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
        $data = new \DateTime();
        $target_dir = "./uploads/";
        $ext = explode('/', $_FILES['image']['type'])[1];
        $target_file = $target_dir . $data->format('d-m-Y-H-i-s') . '.' . $ext;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

        return $target_file;
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
        $obj = $this->book_model->findById($data['id']);
        $this->load("editBook", [
            "obj" => $obj
        ]);
    }

    public function search()
    {
        $objs = $this->book_model->findByName($_GET['query']);
        $this->load("search", [
            "query" => $_GET["query"],
            "objs" => $objs
        ]);
    }

    public function searchBook()
    {
        $objs = $this->book_model->findByName($_GET['query']);
        $this->load("administrative", ["objs" => $objs]);
    }

    public function deleteBook($data)
    {
        $pathToImage = $this->book_model->delete($data['id']);
        unlink($pathToImage);
        header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
    }
}