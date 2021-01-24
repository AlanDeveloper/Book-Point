<?php

namespace MyApp\Models;

use MyApp\Models\classes\Book;

class BookModel extends Model {

    protected function query($sql, $array = [])
    {
        $conn = $this->connect();
        $query = $conn->prepare($sql);
        $query->execute($array);
        $conn = null;

        return $query;
    }

    protected function create_obj($array = null)
    {
        if($array == null) {
            $obj = new Book($_POST['name'], $_POST['author'], $_POST['price'], $_POST['image'], $_POST['amount'], $_POST['synopsis'], $_POST['genre'], $_POST['language']);
        } else {
            $obj = new Book($array['name'], $array['author'], $array['price'], $array['image'], $array['amount'], $array['synopsis'], $array['genre'], $array['language']);
            $obj->setId($array['id']);
        }
        return $obj;
    }
    
    public function findAll()
    {
        $sql = 'SELECT * FROM "book"';

        $objs = [];
        $result = $this->query($sql);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function insert()
    {
        if(!$this->findName()) {

            $obj = $this->create_obj();
            $sql = 'INSERT INTO "book" (name, author, image, price, amount, language, synopsis, genre) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $array = array(
                $obj->getName(),
                $obj->getAuthor(),
                $obj->getImage(),
                $obj->getPrice(),
                $obj->getAmount(),
                $obj->getLanguage(),
                $obj->getSynopsis(),
                $obj->getGenre()
            );
            $this->query($sql, $array);
    
            return $obj;
        } else { return 'O nome que você digitou já foi cadastrado.'; }
    }

    public function findByOrderAmount()
    {
        $sql = 'SELECT * FROM "book" order by amount asc';
        $array = array();

        $objs = [];
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function findByCategory($category)
    {
        $sql = 'SELECT * FROM "book" WHERE genre ilike ?';
        $array = array($category);

        $objs = [];
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function findName()
    {
        $sql = 'SELECT * FROM "book" WHERE name ilike trim(?)';
        $array = array($_POST['name']);

        $result = $this->query($sql, $array);
        return $result->rowCount() == 1 ? true : false;
    }

    public function findByName($name)
    {
        $sql = 'SELECT * FROM "book" WHERE name ilike ?';
        $array = array('%'.$name.'%');

        $objs = [];
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function findById($id)
    {
        $sql = 'SELECT * FROM "book" WHERE id = ?';
        $array = array($id);

        $result = $this->query($sql, $array);
        $result = $result->fetch();

        return $this->create_obj($result);
    }

    public function delete($id)
    {
        $sql = 'SELECT image FROM "book" WHERE id = ?';
        $array = array($id);
        $pathToImage = $this->query($sql, $array);

        $sql = 'DELETE FROM "book" WHERE id = ?';
        $array = array($id);
        $this->query($sql, $array);
        
        return $pathToImage;
    }
}