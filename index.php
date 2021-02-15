<?
require 'php/ExelParser.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/src/SimpleXLSX.php';

echo '<h1>Загрузка EXEL в бд</h1>';

if (isset($_FILES['file'])) {
    if ( $xlsx = SimpleXLSX::parse( $_FILES['file']['tmp_name'] ) ) {
        $_FILES['file']['name'] = time() . "_xlsx";
        $exelPars = new ExelParser($xlsx->rows(), $_FILES['file']['name']);
        $exelPars->start();
        echo 'Создана таблица ' . $_FILES['file']['name'] . ' в неё внесены данные.';
    } else {
        echo SimpleXLSX::parseError();
    }
}
echo '<h2>Выберите файл</h2>
<form method="post" enctype="multipart/form-data">
*.XLSX <input type="file" name="file"  />&nbsp;&nbsp;<input type="submit" value="Загрузить" />
</form>';
?>
</body>
</html>
