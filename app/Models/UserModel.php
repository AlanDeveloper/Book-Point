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
    }

    public function save()
    {
        $obj = $this->create_obj();
        $sql = 'INSERT INTO "user" (name, email, password) VALUES (?, ?, ?)';
        $array = array(
            $obj->getName(),
            $obj->getEmail(),
            MD5($obj->getPassword())
        );
        $this->query($sql, $array);
    }
}