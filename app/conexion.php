<?php
$servername = "db"; // Nombre del servicio de MariaDB en Docker
$username = "root"; // Usuario por defecto
$password = "your_password"; // Cambia esto por tu contraseña
$dbname = "nombre_de_la_base_de_datos"; // Nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>