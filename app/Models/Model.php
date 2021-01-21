<?php

namespace MyApp\Models;

use Csanquer\Silex\PdoServiceProvider\Provider\PdoServiceProvider;
use Silex\Application;

class Model {

    protected function connect() {
        // foreach(\PDO::getAvailableDrivers() as $driver) {
        //    echo $driver;
        // }
        // $db = new \PDO(URL_DATABASE);
        // $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // return $db;
        $dbopts = parse_url(getenv('DATABASE_URL'));
        $app = new Application();
        $app->register(new Csanquer\Silex\PdoServiceProvider\Provider\PDOServiceProvider('pdo'),
            array(
                'pdo.server' => array(
                'driver'   => 'pgsql',
                'user' => $dbopts["user"],
                'password' => $dbopts["pass"],
                'host' => $dbopts["host"],
                'port' => $dbopts["port"],
                'dbname' => ltrim($dbopts["path"],'/')
                )
            )
        );

        return $app['pdo'];
    }
}