<?php

$dbHost ='Localhost';
$dbUsername ='root';
$dbPassword ='';
$dbName ='sistemaloja';


$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_errno) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
} else {
     
}


?>