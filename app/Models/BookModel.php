<?php

namespace MyApp\Models;

use MyApp\Models\classes\Book;

class BookModel extends Model {

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

    public function insert()
    {
        if($this->findBy('name', $_POST['name'], 'exact')) return 'O nome que você digitou já foi cadastrado.';

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
    }

    public function save($data)
    {
        $obj = $this->findBy('id', $data);
        $obj2 = $this->create_obj();
        if(!empty($this->findBy('name', $obj2->getName(), 'exact')) and $obj[0]->getName() != $obj2->getName()) {
            if($obj2->getName() == $this->findBy('name', $obj2->getName(), 'exact')[0]->getName()) return 'O nome que você digitou já foi cadastrado.';
        }
        
        if($_FILES['image']['name'] == '') $obj2->setImage($obj[0]->getImage());
        $obj2->setId($data);
        $sql = 'UPDATE "book" SET name = ?, author = ?, image = ?, price = ?, amount = ?, language = ?, synopsis = ?, genre = ? WHERE id = ?';
        $array = array(
            $obj2->getName(),
            $obj2->getAuthor(),
            $obj2->getImage(),
            $obj2->getPrice(),
            $obj2->getAmount(),
            $obj2->getLanguage(),
            $obj2->getSynopsis(),
            $obj2->getGenre(),
            $obj2->getId(),
        );
        $this->query($sql, $array);

        return $obj2;
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

    public function findBy($param, $data, $type = 'partially')
    {
        if(($param == 'name' or $param == 'genre') and $type == 'partially') {
            $sql = "SELECT * FROM book WHERE $param ilike trim(?)";
            $array = array("%$data%");
        } else {
            $sql = "SELECT * FROM book WHERE $param = ?";
            $array = array($data);
        }
        
        $objs = [];
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function findByOrder($param, $data)
    {
        $sql = "SELECT * FROM book ORDER BY $param $data";
        $array = array();

        $objs = [];
        $result = $this->query($sql, $array);
        $result = $result->fetchAll();
        foreach($result as $obj) {
            array_push($objs, $this->create_obj($obj));
        }

        return $objs;
    }

    public function delete($data)
    {
        $sql = 'SELECT image FROM "book" WHERE id = ?';
        $array = array($data);
        $pathToImage = $this->query($sql, $array);

        $sql = 'DELETE FROM "book" WHERE id = ?';
        $array = array($data);
        $this->query($sql, $array);
        
        return $pathToImage;
    }
}