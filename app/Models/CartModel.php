<?php

namespace MyApp\Models;

class CartModel extends Model {

    public function insert($data) {
        $sql = 'INSERT INTO "cart" (id_user, id_book) VALUES (?, ?)';
        $array = array(
            $_SESSION['id'],
            $data
        );
        $this->query($sql, $array);
        
        return true;
    }

    public function find($data)
    {
        $sql = 'SELECT * FROM "cart" WHERE id_book = ? AND id_user = ?';

        $array = array(
            $data,
            $_SESSION['id']
        );
        $result = $this->query($sql, $array);
        if($result->rowCount() == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM "cart" WHERE id_user = ?';

        $objs = [];
        $array = array($_SESSION['id']);
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $obj);
        }

        return $objs;
    }

    public function delete($data)
    {
        $sql = 'DELETE FROM "cart" WHERE id_book = ?';
        $array = array($data);
        $this->query($sql, $array);
        
        return true;
    }

    public function deleteAll()
    {
        $sql = 'DELETE FROM "cart" WHERE id_user = ?';
        $array = array($_SESSION['id']);
        $this->query($sql, $array);
        
        return true;
    }
}