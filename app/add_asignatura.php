<?php
session_start();
// phpinfo();
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$creditos = $_POST['creditos'];
$convocatorias_usadas = $_POST['convocatorias_usadas'];
$dni = $_SESSION['dniUsuario'];
$año = $_POST['año'];

$query = "INSERT INTO asignaturas (nombre, descripcion, creditos, convocatorias_usadas , año, dni) VALUES ('$nombre', '$descripcion', '$creditos', '$convocatorias_usadas', '$año', '$dni')";
$result = mysqli_query($conn, $query);

if ($result) {

    header('Location: dashboard.php');
} else {

    echo "Error al añadir: " . $conn->error;
    
}

mysqli_close($conn);
?>

