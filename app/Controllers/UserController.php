<?php

namespace MyApp\Controllers;

use MyApp\Models\UserModel;

class UserController extends Controller {

    protected function verifyForm()
    {
        if(!empty($_POST['name'])) {
            if(strlen($_POST['name']) > 50) return 'Campo nome não está no tamanho especificado.';
        }
        if(strlen($_POST['email']) > 255) {
            return 'Campo e-mail não está no tamanho especificado.';
        }
        if(strlen($_POST['password']) > 50 or strlen($_POST['password']) < 6) {
            return 'Campo senha não está no tamanho especificado.';
        }
        if(!empty($_POST['repassword'])) {
            if(strlen($_POST['repassword']) > 50 or strlen($_POST['repassword']) < 6) return 'Campo nova senha não está no tamanho especificado.';
            if($_POST['password'] != $_POST['repassword']) return 'Campos possuem senhas diferentes.';
        } 
        if(!empty($_POST['newpassword'])) {
            if(strlen($_POST['newpassword']) > 50 or strlen($_POST['newpassword']) < 6) return 'Campo nova senha não está no tamanho especificado.';
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

    public function auth()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load('login');
        } else {
            $error = $this->verifyForm();
            if($error == '') {

                $result = $this->user_model->find();
                if (gettype($result) == 'object') {
                    $this->startSession($result);
                    $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
                } else { $this->loadErrors('login', $result); }
            } else {
                $this->loadErrors('login', $error);
            }
        }
    }

    public function add()
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load('addUser');
        } else {
            $error = $this->verifyForm();
            if($error == '') {

                $result = $this->user_model->insert();
                if (gettype($result) == 'object') {
                    $this->startSession($result);
                    $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
                } else { $this->loadErrors('addUser', $result); }
            } else {
                $this->loadErrors('addUser', $error);
            }
        }
    }

    public function edit($data)
    {
        if($_SERVER['REQUEST_METHOD'] == 'GET') {
            $this->load('editUser');
        } else {
            $error = $this->verifyForm();
            if($error == '') {

                $result = $this->user_model->save();
                if (gettype($result) == 'object') {
                    $this->startSession($result);
                    $_SESSION['logged'] ? header('Location: '. strval($_ENV['BASE_URL']) .'/') : null;
                } else { $this->loadErrors('editUser', $result); }
            } else {
                $this->loadErrors('editUser', $error);
            }
        }
    }

    public function delete()
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