<?php

namespace MyApp\Controllers;

class UserController extends Controller {

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

    public function login()
    {
        $this->load('login');
    }

    public function authLogin()
    {
        $error = $this->verifyForm();
        if($error == '') {
            echo 'Vou verificar seu login';
        } else {
            $this->load('login', [
                'form' => [
                    'name' => isset($_POST['name']) ?  $_POST['name'] : '',
                    'email' => $_POST['email'],
                    'password' => $_POST['password']
                ],
                'error' => $error
            ]);
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
            echo 'Vou te cadastrar';
        } else {
            $this->load('register', [
                'form' => [
                    'name' => isset($_POST['name']) ?  $_POST['name'] : '',
                    'email' => $_POST['email'],
                    'password' => $_POST['password']
                ],
                'error' => $error
            ]);
        }
    }
}