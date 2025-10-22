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

	if ($result->num_rows > 0 && $row['contrasena'] === $contrasena) {
		$_SESSION['usuario'] = $usuario;
		$_SESSION['idU'] = $row['idU'];
		header("Location: items.php");
		exit();
	} else {
		echo "<script> window.alert('Nombre de usuario o contraseña incorrectos'); </script>";
	}
	$conn->close();
}

ob_end_flush();	
?>

<html>
<head>
	<title> Inicio de sesión </title>
    <link rel="stylesheet" type="text/css" href="inicioStyle.css">
</head>
	<body class="fondo-rosaMedio">
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