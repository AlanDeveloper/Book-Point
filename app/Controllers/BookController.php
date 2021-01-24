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
        $result = $this->book_model->insert();
        if (gettype($result) == 'object') {
            header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
        } else { $this->loadErrors('addBook', $result); }
    }

    public function search()
    {
        $objs = $this->book_model->findByName($_GET['query']);
        $this->load("search", [
            "query" => $_GET["query"],
            "objs" => $objs
        ]);
    }

    public function deleteBook($data)
    {
        $this->book_model->delete($data['id']);
        header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
    }
}