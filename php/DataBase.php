<?php

class DataBase {

    private $link;
    protected $config = [];

    public function __construct()
    {
        $this->connect();
    }
//Функция для подключения к бд
    private function connect()
    {
        $config = require_once 'config.php';

        $this->config = $config;

        if($config['db_name'] == ''){
            echo 'Нужно задать базу данных в конфиге /php/config.php - "db_name", а так же пароль и логин';
            exit();
        }

        $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db_name'].';charset='.$config['charset'];

        $this->link = new PDO($dsn, $config['username'], $config['password']);

        return $this;

    }
//Функция для запросов в бд
    public function query($sql)
    {
        $sth = $this->link->prepare($sql);

        $sth->execute();

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        if($result === false){
            return [];
        }

        return $result;
    }
}
