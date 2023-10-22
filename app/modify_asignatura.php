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

$asignatura_id = $_POST['asignatura_id'];
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$creditos = $_POST['creditos'];
$convocatorias_usadas = $_POST['convocatorias_usadas'];
$año =$_POST['año'];
$dni = $_SESSION['dniUsuario'];



$query = "UPDATE asignaturas SET nombre = '$nombre', descripcion = '$descripcion', creditos = '$creditos', convocatorias_usadas = '$convocatorias_usadas', año = '$año' WHERE id = '$asignatura_id' AND dni = '$dni'";
$result = mysqli_query($conn, $query);

if ($result) {
    header('Location: dashboard.php');
} else {
    header('Location: dashboard.php?error=modify_asignatura_failed');
}

mysqli_close($conn);
?>

