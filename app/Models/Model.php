<?php

namespace MyApp\Models;

use PDO;

class Model {

    protected function connect()
    {
        $db_url = strval($_ENV['DB_URL']);
        $db_user = strval($_ENV['DB_USER']);
        $db_password = strval($_ENV['DB_PASSWORD']);

        $db = new \PDO($db_url, $db_user, $db_password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;
    }
    
    protected function query($sql, $array = [])
    {
        $conn = $this->connect();
        $query = $conn->prepare($sql);
        $query->execute($array);
        // $conn = null;

        return $query;
    }
}