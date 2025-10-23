<?php
//para no repetir la conexion en todos los archivos, centralizamos la conexion aqui y llamamos en cada archivo para conectar
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4"); //PAra que permita tildes y ñ
?>