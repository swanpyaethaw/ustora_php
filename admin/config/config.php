<?php 

define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASS','');
define('MYSQL_DATABASE','ustora');

$option = [PDO::ATTR_ERRMODE => PDO:: ERRMODE_EXCEPTION];

$pdo = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DATABASE,MYSQL_USER,MYSQL_PASS,$option);



