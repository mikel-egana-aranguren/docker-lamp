<?php

session_start(); //iniciar sesion con php
// generar un token CSRF si no existe
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
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
	// validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Error: CSRF token inválido.');
    }
	// guardar la información del formulario
    $usuario=$_POST['usuario'];
    $contrasena=$_POST['contrasena'];

	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$stmt = $conn->prepare("SELECT idU, contrasena, rol  FROM usuarios WHERE usuario = ?");
	$stmt->bind_param("s", $usuario);
	$stmt->execute();
	//se ejecuta la instrucción
	$result = $stmt->get_result();
    //pillamos la contraseña de la bd
    $row = $result->fetch_assoc();

	if ($result->num_rows > 0 && password_verify($contrasena, $row['contrasena'])) {
		//crear sesión
		$_SESSION['usuario'] = $usuario;
		$_SESSION['idU'] = $row['idU'];
		$_SESSION['rol'] = $row['rol'];
		$stmt->close();
		$conn->close();
		header("Location: items.php");
		exit();
	} else {
		echo "<script> window.alert('Nombre de usuario o contraseña incorrectos'); </script>";
	}
	$stmt->close();
	$conn->close();
}

ob_end_flush();	
?>

<html>
<head>
	<title> Inicio de sesión </title>
    <link rel="stylesheet" type="text/css" href="inicioStyle.css">
</head>
	<body class="login">
		<h1>Rellena los datos de inicio de sesión: </h1>
		<img src="/img/gatoMoviendoPatas.gif" alt="Gato" class="gato">
	<form class="login" name="login_form" method="post" >
		Nombre de usuario:<br><input type="text" name="usuario" required><br>
		Contraseña:<br> <input type="password" name="contrasena" required> <br>
		<br>
		<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<input type="submit" value="Iniciar sesión" name="login_submit" class="btn_login">
	</form>
		
	<div class="button-container" >
		<a href="index.php" class="buttonVolver">Volver a inicio</a>
	</div>
	
<html>