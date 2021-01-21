<?php

namespace MyApp\Models;

use MyApp\Models\classes\User;

class UserModel extends Model {

    protected function create_obj($array = null)
    {
        if($array == null) {
            $obj = new User($_POST['name'], $_POST['email'], $_POST['password']);
        } else {
            $obj = new User($array['name'], $array['email'], $array['password']);
            $obj->setId($array['id']);
        }
        return $obj;
    }

    protected function query($sql, $array = [])
    {
        $conn = $this->connect();
        $query = $conn->prepare($sql);
        $query->execute($array);

        return $query;
    }

    public function save()
    {
        if(!$this->findEmail()) {

            $obj = $this->create_obj();
            $sql = 'INSERT INTO "user" (name, email, password) VALUES (?, ?, ?)';
            $array = array(
                $obj->getName(),
                $obj->getEmail(),
                MD5($obj->getPassword())
            );
            $this->query($sql, $array);
    
            echo 'iniciar sessão';
            return '';
        } else { return 'O e-mail que você digitou já foi cadastrado.'; }
    }

    public function auth()
    {
        $sql = 'SELECT * FROM "user" WHERE email = ? AND password = ?';
        $array = array(
            $_POST['email'],
            MD5($_POST['password'])
        );

        $result = $this->query($sql, $array);
        if($result->rowCount() == 1){
            $info = $result->fetch();
            echo 'iniciar sessão';
            return '';
        } else {
            return $this->findEmail() ? 'A senha que você digitou está incorreta.' : 'Os dados que você digitou estão incorretos.';
        }
    }

    public function findEmail()
    {
        $sql = 'SELECT * FROM "user" WHERE email = ?';
        $array = array($_POST['email']);

        $result = $this->query($sql, $array);
        return $result->rowCount() == 1 ? true : false;
    }
}