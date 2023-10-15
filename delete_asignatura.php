<?php
session_start();

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$asignatura_id = $_POST['asignatura_id'];
$dni = $_SESSION['dniUsuario'];

// Consulta SQL para eliminar la asignatura
$sql = "DELETE FROM asignaturas WHERE id = '$asignatura_id' AND dni = '$dni'";
$result = mysqli_query($conn, $sql);

if ($result) {
    header('Location: dashboard.php');
} else {
    header('Location: dashboard.php?error=delete_asignatura_failed');
}

mysqli_close($conn);
?>

