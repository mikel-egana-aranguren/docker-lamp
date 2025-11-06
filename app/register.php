<?php
session_start(); //iniciar sesion con php
// generar un token CSRF si no existe
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
//parámetros para la conexión a la bd
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

//conexión a la bd:
$conn = new mysqli($servername, $username, $password, $dbname);

//comprobar la conexión
if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error); //si error -> mnsj por pantalla
	die("Connection failed: ");
}


// comprobar si se ha enviado el formulario
if (isset($_POST['register_submit'])) {
	// comprobar que el método de la solicitud es POST
	if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        die('método incorrecto.');
    }
	// comprobar que no hay parámetros inesperados
	$param_esperados = ['register_submit','csrf_token','nombre','apellido','numDni','letraDni','tlfn','fNacimiento','email','usuario','contrasena'];
    foreach ($_POST as $param => $valor) {
        if (!in_array($param, $param_esperados, true)) {
            http_response_code(400);
            die('parámetro no esperado');
        }
    }
	// validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	http_response_code(403);
	die('Error: CSRF token inválido.');
	}
	// guardar la información del formulario

	//sanitizar entradas
    $nombre= htmlspecialchars(trim($_POST['nombre']));
    $apellido = htmlspecialchars(trim($_POST['apellido']));
    $numDni= htmlspecialchars(trim($_POST['numDni']));
    $letraDni= htmlspecialchars(trim($_POST['letraDni']));
    $tlfn= htmlspecialchars(trim($_POST['tlfn']));
    $fNacimiento = htmlspecialchars(trim($_POST['fNacimiento']));
    $email= filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $usuario = htmlspecialchars(trim($_POST['usuario']));
    $contrasena = $_POST['contrasena']; // esta no hay que hacerlo porque se va a hashear

	//guarda la instrucción de SQL que quere utilizar, en este caso un select
	$stmt = $conn->prepare("SELECT idU FROM usuarios WHERE usuario = ?");
	$stmt->bind_param("s", $usuario);
	$stmt->execute();
	//se ejecuta la instrucción
	$result = $stmt->get_result();
	if ($result ->num_rows > 0){ //comprobar si hay otro usuario con ese nombre de usuario
		echo "<script> window.alert('Escoja otro nombre de usuario, ese no está disponible'); </script>";
		$stmt->close();
	}
	else{
		$stmt->close();
		//hashear la contraseña antes de guardarla
		$hash = password_hash($contrasena, PASSWORD_DEFAULT);
		//guarda la instrucción de SQL que quere utilizar, en este caso un insert
		$stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido,numDni,letraDni,tlfn,fNacimiento,email,usuario,contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssssss", $nombre, $apellido, $numDni, $letraDni, $tlfn, $fNacimiento, $email, $usuario, $hash);
    	//se comprueba si la instrucción se ha ejecutado de forma correcta
		if ($stmt->execute()) {
			//se recoge el id del usuario para despues crear su sesión
			$idUsuario = $stmt->insert_id;
			$_SESSION['user_id'] = $idUsuario;
			$stmt->close();
			$conn->close();
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
    		error_log("DB query failed: " . $conn->error);
			echo "Ha ocurrido un error al procesar el registro.";

			$stmt->close();
    	}
		
	//se cierra la conexión
	$conn->close();
}
}
?>

<html>
<head>
	<title> Registro </title>
	<link rel="stylesheet" type="text/css" href="inicioStyle.css">
	<script src="js/validar_user.js"></script>	
</head>
	<body class="register">
		<h1> Rellena los datos para poder registrarte</h1>
	<form class="register" id="register_form" method="post" >
		Nombre :<br> <input type="text" name="nombre" placeholder="Nombre" required>  <br>
        Apellido:<br><input type="text" name="apellido" placeholder="Apellido" required><br>
		DNI: <br> <input type="text" name="numDni" placeholder="12345678" required> <input type="text" name="letraDni" placeholder="Letra DNI" required> <br>
  		Teléfono:<br> <input type="text" name="tlfn" placeholder="123456789"required><br>
		Fecha de nacimiento:<br> <input type="date" name="fNacimiento"required/><br>
		Email:<br> <input type="text" name="email" placeholder="email@xxx.yyy" required> <br>
		Nombre de usuario<br><input type="text" name="usuario" required><br>
		Contraseña:<br> <input type="password" name="contrasena" required> <br>
		<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
		<input type="submit" value="Registrar" name="register_submit" class="btn_register">
	</form>
	<div class="button-container" >
		<a href="index.php" class="buttonVolver">Volver a inicio</a>
	</div>
	</body>
</html>