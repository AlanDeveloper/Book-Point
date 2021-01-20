<?php

namespace MyApp\Models;

class Model {

    protected function connect() {
        // foreach(\PDO::getAvailableDrivers() as $driver) {
        //    echo $driver;
        // }
        $db = new \PDO(URL_DATABASE);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $db;
    }
}