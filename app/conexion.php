<?php
$servername = "db"; // Nombre del servicio de MariaDB en Docker
$username = "admin"; // Usuario por defecto
$password = "test"; // Cambia esto por tu contraseña
$dbname = "database"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>