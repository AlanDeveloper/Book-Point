<?php

namespace MyApp\Controllers;

use MyApp\Models\UserModel;

class UserController extends Controller {

    protected $user_model;

    public function __construct()
    {
        $this->user_model = new UserModel();
    }

    protected function verifyForm()
    {
        if(!(strlen($_POST['email']) <= 255)) {
            return 'Campo e-mail não está no tamanho especificado.';
        }
        if(!(strlen($_POST['password']) >= 6 and strlen($_POST['password']) <= 50)) {
            return 'Campo senha não está no tamanho especificado.';
        }
        if(isset($_POST['repassword'])) {
            if(!($_POST['repassword'] == $_POST['password'])) {
                return 'Campos senhas não possuem a mesma senha.';
            }
        }
        if(isset($_POST['name'])) {
            if(!(strlen($_POST['name']) <= 50)) {
                return 'Campo nome não está no tamanho especificado.';
            }
        }

        return '';
    }

    protected function loadErrors($page, $error)
    {
        $this->load($page, [
        'form' => [
                'name' => isset($_POST['name']) ?  $_POST['name'] : '',
                'email' => $_POST['email']
            ],
            'error' => $error
        ]);
    }

    protected function startSession($obj) {
        $_SESSION['logged'] = true;
        $_SESSION['name'] = $obj->getName();
        $_SESSION['email'] = $obj->getEmail();
        $_SESSION['password'] = $obj->getPassword();
        $_SESSION['id'] = $obj->getId();
        $_SESSION['admin'] = $obj->getAdmin();
    }

    public function login()
    {
        $this->load('login');
    }

    public function authLogin()
    {
        $error = $this->verifyForm();
        if($error == '') {

            $result = $this->user_model->auth();
            gettype($result) != 'object' ? $this->loadErrors('login', $result) :  $this->startSession($result);
        } else {
            $this->loadErrors('login', $error);
        }

        $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
    }
    
    public function register()
    {
        $this->load('register');
    }
    
    public function authRegister()
    {
        $error = $this->verifyForm();
        if($error == '') {

            $result = $this->user_model->save();
            gettype($result) != 'object' ? $this->loadErrors('register', $result) :  $this->startSession($result);
        } else {
            $this->loadErrors('register', $error);
        }

        $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
    }

    public function editProfile()
    {
        $this->load('editProfile');
    }

    public function deleteProfile()
    {
        $this->user_model->delete();
        $this->loggout();
    }

    public function loggout()
    {
        session_unset();
        header('Location: '. strval($_ENV['BASE_URL']) .'/');
    }
}