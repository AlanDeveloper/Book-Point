<?php

namespace MyApp\Models;

class Cart extends Model {

    public function insert() {
        $sql = 'INSERT INTO "cart" (id_user, id_book) VALUES (?, ?)';
        $array = array(
            $_SESSION['id'],
            $_POST['id']
        );
        $this->query($sql, $array);
        
        return true;
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM "cart"';

        $objs = [];
        $result = $this->query($sql);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $obj);
        }

        return $objs;
    }
}