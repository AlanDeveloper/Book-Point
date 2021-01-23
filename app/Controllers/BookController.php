<?php

namespace MyApp\Controllers;

use MyApp\Models\BookModel;

class BookController extends Controller {

    protected $book_model;

    public function __construct()
    {
        $this->book_model = new BookModel();
    }

    public function administrative()
    {
        $objs = $this->book_model->findAll();
        $this->load('administrative', ['objs' => $objs]);
    }

    public function deleteBook($data)
    {
        $this->book_model->delete($data['id']);
        header('Location: '. strval($_ENV['BASE_URL']) .'/administrative');
    }
}