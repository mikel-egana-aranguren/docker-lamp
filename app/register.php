<?php
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
$apellidos = $_POST['apellidos'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$email = $_POST['email'];
$password = $_POST['password'];

// Aquí puedes agregar validaciones adicionales para los datos recibidos, como la validación del DNI

$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fechaNacimiento, email, password) VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fechaNacimiento', '$email', '$password')";


if ($conn->query($sql) === TRUE) {
	header('Location: index.html?message=registered_successfully');
}else {
	header('Location: register.html?error=registration_failed');
}

$conn->close();
?>

