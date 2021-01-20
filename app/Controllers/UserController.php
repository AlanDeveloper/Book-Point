<?php

namespace MyApp\Controllers;

class UserController extends Controller {

    public function login()
    {
        $this->load('login');
    }

    public function register()
    {
        $this->load('register');
    }
}