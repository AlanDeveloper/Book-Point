<?php

namespace MyApp\Models;

class BookModel extends Model {

    protected function query($sql, $array = [])
    {
        $conn = $this->connect();
        $query = $conn->prepare($sql);
        $query->execute($array);
        $conn = null;

        return $query;
    }
    
    public function findAll()
    {
        $sql = 'SELECT * FROM "book"';

        $result = $this->query($sql);
        return $result->fetchAll();
    }

    public function delete($id)
    {
        $sql = 'DELETE FROM "book" WHERE id = ?';
        $array = array($id);

        $result = $this->query($sql, $array);
    }
}