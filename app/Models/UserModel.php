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
        $conn = null;

        return $query;
    }

    public function insert()
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
            
            return $this->auth();
        } else { return 'O e-mail que você digitou já foi cadastrado.'; }
    }

    public function save()
    {
        $obj = $this->create_obj();
        $isValidEmail = $_SESSION['email'] == $obj->getEmail();
        if(isset($_POST['password'])) {
            $isValidPassword = $_SESSION['password'] == $obj->getPassword();
            if(!$isValidPassword) {
                return 'A senha que você digitou está incorreta.';
            }
        }
        if(!$isValidEmail) {
            if($this->findEmail()) {
                return 'O e-mail que você digitou já foi cadastrado.';
            } else {
                $sql = 'UPDATE "user" SET name = ?, email = ?, password = ? WHERE id = ?';
                $array = array(
                    $obj->getName(),
                    $obj->getEmail(),
                    MD5($obj->getPassword()),
                    $obj->getId()
                );
                $this->query($sql, $array);
        
                return $obj;
            }
        } else {
            $sql = 'UPDATE "user" SET name = ?, password = ? WHERE id = ?';
            $array = array(
                $obj->getName(),
                MD5($obj->getPassword()),
                $obj->getId()
            );
            $this->query($sql, $array);
    
            return $obj;
        }

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
            $obj = $this->create_obj($result->fetch());
            return $obj;
        } else {
            return $this->findEmail() ? 'A senha que você digitou está incorreta.' : 'Os dados que você digitou estão incorretos.';
        }
    }

    public function delete()
    {
        $sql = 'DELETE FROM "user" WHERE id = ?';
        $array = array($_SESSION['id']);

        $result = $this->query($sql, $array);
    }

    public function findEmail()
    {
        $sql = 'SELECT * FROM "user" WHERE email = ?';
        $array = array($_POST['email']);

        $result = $this->query($sql, $array);
        return $result->rowCount() == 1 ? true : false;
    }

    public function find($obj)
    {
        $sql = 'SELECT * FROM "user" WHERE email = ? AND password = ?';
        $array = array(
            $obj->getEmail(),
            $obj->getPassword()
        );

        $result = $this->query($sql, $array);
        return $result->fetch();
    }
}