<?php

$dbHost ='loja_mega_xerox_db';
$dbUsername ='root';
$dbPassword ='Raposo88125442@@';
$dbName ='sistemaloja';


$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_errno) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
} else {
     
}


?>
