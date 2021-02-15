<?php
require 'DataBase.php';

class ExelParser extends DataBase
{
    private $columns = [];
    private $rows = [];
    private $table = '';

//Отделаю шапку и тело
    public function __construct($exel, $name = 'xlsx_table')
    {
        parent::__construct();
        $this->table = $name;
        $this->columns = $exel[0];
        unset($exel[0]);
        $this->rows = $exel;

    }
//    Стартовая функция
    public function start(){
//        Проверка, есть ли таблица
        if (empty($this->query("SHOW TABLES FROM ".$this->config['db_name']." LIKE '".$this->table."';"))) {
            $this->addTable();
        }
        //        Проверка, есть ли столбцы
        foreach ($this->columns as $column){
            $this->checkColumn($column);
        }
        //       Добавление данных в строки
        foreach ($this->rows as $row){
            $this->addRow($row);
        }
    }
//    Проверяю, есть ли столбцы, если нет, тогда добавляю
    private function checkColumn($name){
//        Получаю список столбцов
        $answer = $this->query("SELECT COLUMN_NAME
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_NAME = '".$this->table."'
        ");
//Проверяю есть ли столбец
        foreach ($answer as $col_name) {
            if ($col_name['COLUMN_NAME'] !== $name) {
                $this->addColumn($name);
            }
        }
    }
//    Добавить таблицу
    private function addTable(){
        $this->query("CREATE TABLE `".$this->table."` ( id int(11) unsigned auto_increment primary key )");
    }
//    Добавляю колонку
    private function addColumn($name){
        $this->query("ALTER TABLE ".$this->config['db_name'].".".$this->table." ADD COLUMN `".$name."` text (3000)");
    }
//    Добавление данных в столбцы
    private function addRow($row){
        $sqlColumn = '';
        $sqlRow = '';
//        Рисую данные для запроса
        for ($i=0; $i<count($row); $i++){
            $ppp = $i == (count($row) - 1) ? '' : ',';
            $sqlColumn .= '`'. $this->columns[$i] . '`' . $ppp;
            $sqlRow .= "'" . $row[$i] . "'" . $ppp;
        }

        $this->query("INSERT INTO ".$this->config['db_name'].".".$this->table." (".$sqlColumn.")  VALUES (".$sqlRow.");");
    }
}