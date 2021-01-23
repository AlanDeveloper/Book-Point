<?php

namespace MyApp\Controllers;

use MyApp\Models\UserModel;

class UserController extends Controller {

    protected $user_model;

    public function __construct()
    {
        $this->user_model = new UserModel();
    }

    protected function verifyForm($request_password = true, $to_compare = true)
    {
        if(!(strlen($_POST['email']) <= 255)) {
            return 'Campo e-mail não está no tamanho especificado.';
        }
        if(isset($_POST['name'])) {
            if(!(strlen($_POST['name']) <= 50)) {
                return 'Campo nome não está no tamanho especificado.';
            }
        }
        if($request_password) {

            if(!(strlen($_POST['password']) >= 6 and strlen($_POST['password']) <= 50)) {
                return 'Campo senha não está no tamanho especificado.';
            }
            if(isset($_POST['repassword']) and $to_compare) {
                if(!($_POST['repassword'] == $_POST['password'])) {
                    return 'Campos senhas não possuem a mesma senha.';
                }
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
            if (gettype($result) == 'object') {
                $this->startSession($result);
                $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
            } else { $this->loadErrors('login', $result); }
        } else {
            $this->loadErrors('login', $error);
        }
    }
    
    public function register()
    {
        $this->load('register');
    }
    
    public function authRegister()
    {
        $error = $this->verifyForm();
        if($error == '') {

            $result = $this->user_model->insert();
            if (gettype($result) == 'object') {
                $this->startSession($result);
                $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
            } else { $this->loadErrors('register', $result); }
        } else {
            $this->loadErrors('register', $error);
        }

    }

    public function editProfile()
    {
        $this->load('editProfile');
    }

    public function saveProfile()
    {
        $error = $_POST['repassword'] != '' ? $this->verifyForm(true, false) : $this->verifyForm(false, false);
        if($error == '') {

            $result = $this->user_model->save();
            if (gettype($result) == 'object') {
                $this->startSession($result);
                $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
            } else { $this->loadErrors('editProfile', $result); }
        } else {
            $this->loadErrors('editProfile', $error);
        }
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