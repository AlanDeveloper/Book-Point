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
        // foreach($objs as $obj) {
        //     echo $obj['name'];
        // } 
        // var_dump($objs);
        $this->load('administrative', ['objs' => $objs]);
    }
}