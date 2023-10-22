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
$dni = $_SESSION['dniUsuario'];



$query = "SELECT nombre, apellidos, email, telefono, password, dni, fechaNacimiento FROM usuarios WHERE dni = '$dni'";

$result = mysqli_query($conn, $query);
if ($result) {
    $usuario = mysqli_fetch_assoc($result);
} else {
    echo ("No se ha podido obtener los valores del usuario");
    header('Location: dashboard.php?error=modify_asignatura_failed');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - Plataforma de Asignaturas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h1>Editar usuario</h1>
    <form id="registerForm" action="modify_usuario.php" method="post">
    	
    	<input type="hidden" name="dni" value="<?php echo $dni; ?>">
    	
        <label for="nombre">NOMBRE:</label>
        <input type="text" id = "nombre" name="nombre" value="<?php echo $usuario['nombre']; ?>">
       
        <label for="apellidos">APELLIDOS:</label>
	    <input type="text" id="apellidos" name="apellidos" required value="<?php echo $usuario['apellidos']; ?>">
        
        <label for="telefono">TELEFONO:</label>
        <input type="text" id="telefono" name="telefono" required pattern= "[0-9]{9}" required value="<?php echo $usuario['telefono']; ?>">
        
        <label for="fechaNacimiento">FECHA NACIMIENTO:</label>
        <input type="date" id="fechaNacimiento" name="fechaNacimiento" required value="<?php echo $usuario['fechaNacimiento']; ?>">
        
        <label for="password">CONTRASEÑA:</label>
        <input type="password" id="password" name="password" required value="<?php echo $usuario['password']; ?>">
        <button type="submit">Editar</button>
    </form>
</div>
<script src="script.js"></script>
</body>
</html>

