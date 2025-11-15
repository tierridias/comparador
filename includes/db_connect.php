<?php

$host = "localhost";
$db = "comparador";
$user = "root";
$pass = "";

$conn = new mysqli($host, $db, $user, $pass);

if ($conn -> connect_error){
    die("Ligacao falhou:" . $conn->connect_error);
}

?>