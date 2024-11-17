<?php 

$host = "localhost";
$dbname= "integrador_db";
$user = "root";
$password = "";

try{
    $pdo = new PDO("mysql:host=$host;dbname=$dbname",$user,$password);
}catch (PDOException $ex){
    echo $ex->getMessage();
}

