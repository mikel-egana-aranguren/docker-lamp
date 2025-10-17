<?php

session_start(); //iniciar sesion con php
ob_start();
//parámetros para la conexión a la bd
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

//conexión a la bd:
$conn = new mysqli($servername, $username, $password, $dbname);

//comprobar la conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); //si error -> mnsj por pantalla
}


// comprobar si se ha enviado el formulario
if (isset($_POST['login_submit'])) {
	// guardar la información del formulario
    $usuario=$_POST['usuario'];
    $contrasena=$_POST['contrasena'];

	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$sql = "SELECT idU, usuario, contrasena from usuarios where usuario = '" . $usuario . "'";
	//se ejecuta la instrucción
	$result = $conn->query($sql);
    //pillamos la contraseña de la bd
    $row = $result->fetch_assoc();

	if ($result->num_rows > 0){ //comprobar si el usuario existe y si la contraseña es correcta
		$_SESSION['usuario'] = $usuario;
		$_SESSION['idU'] = $row['idU'];
		header("Location: items.php");
		exit(); // Asegura que el código se detiene después de la redirección
		}
	else{
		echo "<script> window.alert('Nombre de usuario o contraseña incorrectos'); </script>";
	}
	$conn->close();
}

ob_end_flush();	
?>

<html>
<head>
	<title> Inicio de sesión </title>
	<script src="validar_user.js"></script>
</head>
	<body>
	<form name="login_form" method="post" >
		<p align="center">Rellena los datos de inicio de sesión</p>
		Nombre de usuario<br><input type="text" name="usuario" required><br>
		Contraseña:<br> <input type="text" name="contrasena" required> <br>
		<br>
		<input type="submit" value="Iniciar sesión" name="login_submit" style="color:black; background-color:lightpink;">
	</form>
		
	<div class="button-container">
		<a href="index.php" class="button">volver</a>
	</div>
	
<html>