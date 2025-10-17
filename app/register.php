<?php

session_start(); //iniciar sesion con php
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
if (isset($_POST['register_submit'])) {
	// guardar la información del formulario
    $nombre = $_POST['nombre'];
    $apellido= $_POST['apellido'];
    $numDni = $_POST['numDni'];
    $letraDni = $_POST['letraDni'];
    $tlfn=$_POST['tlfn'];
    $fNacimiento=$_POST['fNacimiento'];
    $email=$_POST['email'];
    $usuario=$_POST['usuario'];
    $contrasena=$_POST['contrasena'];

	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$sql = "SELECT usuario from usuarios where usuario = '" . $usuario . "'";
	//se ejecuta la instrucción
	$result = $conn->query($sql);
	if ($result ->num_rows > 0){ //comprobar si hay otro usuario con ese nombre de usuario
		echo "<script> window.alert('Escoja otro nombre de usuario, ese no está disponible'); </script>";}
	else{
		//guarda la instrucción de SQL que quere utilizar, en este caso un insert
		$sql = "INSERT INTO usuarios (nombre, apellido,numDni,letraDni,tlfn,fNacimiento,email,usuario,contrasena) VALUES ('". $nombre ."', '" . $apellido . "' , '" . $numDni . "', '" . $letraDni . "', '" . $tlfn . "' , '" . $fNacimiento . "' , '" . $email . "' , '" . $usuario . "' , '" . $contrasena . "'  )";
    	//se comprueba si la instrucción se ha ejecutado de forma correcta
		if ($conn->query($sql) === TRUE) {
			//se recoge el id del usuario para despues crear su sesión
			$sql = "SELECT idU from usuarios where usuario = '" . $usuario . "' and contrasena='" . $contrasena . "'";
			$result = $conn->query($sql);
			$returnedValues = $result->fetch_assoc();
			$_SESSION['user_id'] = $returnedValues['idU'];
			echo "<script>
			window.alert('Te has registrado correctamente');
			window.location.href = 'login.php';
			</script>";

			//se cierra la conexión
			$conn->close();
			header("Location: login.php");
			exit();
		} 
		else {
			//la instrucción no es válida
    		echo "Error: " . $sql . "<br>" . $conn->error;
    	}
		
	//se cierra la conexión
	$conn->close();
}
}
?>

<html>
<head>
	<title> Registro </title>
	<script src="js/validar_user.js"></script>	
</head>
	<body>
	<form id="register_form" method="post" >
		<p align="center">Rellena los datos para poder registrarte:</p>
		Nombre :<br> <input type="text" name="nombre" placeholder="Nombre" required>  <br>
        Apellido:<br><input type="text" name="apellido" placeholder="Apellido" required><br>
		DNI: <br> <input type="text" name="numDni" placeholder="12345678" required> <input type="text" name="letraDni" placeholder="Letra DNI" required> <br>
  		Teléfono:<br> <input type="text" name="tlfn" placeholder="123456789"required><br>
		Fecha de nacimiento:<br> <input type="date" name="fNacimiento"required/><br>
		Email:<br> <input type="text" name="email" placeholder="email@xxx.yyy" required> <br>
		Nombre de usuario<br><input type="text" name="usuario" required><br>
		Contraseña:<br> <input type="text" name="contrasena" required> <br>
		<br>
		<input type="submit" value="Registrar" name="register_submit" style="color:black; background-color:lightpink;">
	</form>
	<div class="button-container">
		<a href="index.php" class="button">Volver a inicio</a>
	</div>
	</body>
<html>