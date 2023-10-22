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

$consulta_dni = "SELECT * FROM usuarios WHERE dni = '$dni'";
$result_dni = mysqli_query($conn, $consulta_dni);

$consulta_email = "SELECT * FROM usuarios WHERE email = '$email'";
$result_email = mysqli_query($conn, $consulta_email);



if (mysqli_num_rows($result_dni) > 0) {
    echo "El DNI ya está en uso.";
    echo '<script type="text/javascript">window.alert("El dni ya está en uso por otro usuario."); window.location.href = "register.html?error=email_in_use";</script>';
} elseif (mysqli_num_rows($result_email) > 0) {
    echo '<script type="text/javascript">window.alert("El correo electrónico ya está en uso por otro usuario."); window.location.href = "register.html?error=email_in_use";</script>';
    header('Location: register.html?error=email_in_use');
} else {


$sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fechaNacimiento, email, password) VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fechaNacimiento', '$email', '$password')";

}
if ($conn->query($sql) === TRUE) {
    echo '<script type="text/javascript">window.alert("Registro exitoso"); window.location.href = "inicio.html";</script>';
}else 
{
	echo "No se consiguio el registro";
	header('Location: register.html?error=registration_failed');
}


$conn->close();

