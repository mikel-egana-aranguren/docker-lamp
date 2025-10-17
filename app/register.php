<?php
<<<<<<< Updated upstream


?>
=======
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["nombre"];
    $apellidos = $_POST["apellidos"];
    $dni = strtoupper($_POST["dni"]);
    $telefono = $_POST["telefono"];
    $fecha = $_POST["fecha"];
    $email = $_POST["email"];
    $usuario = $_POST["usuario"];
    $pass = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO usuarios 
        (nombre, apellidos, dni, telefono, fecha_nacimiento, email, nombre_usuario, contrasena) 
        VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss", $nombre, $apellidos, $dni, $telefono, $fecha, $email, $usuario, $pass);
    
    if ($stmt->execute()) {
        echo "<p>Registro completado ✅ <a href='/login'>Iniciar sesión</a></p>";
    } else {
        echo "<p>Error: {$stmt->error}</p>";
    }
}
?>

<form id="register_form" method="POST" onsubmit="return validarRegistro()">
  <input name="nombre" placeholder="Nombre" required><br>
  <input name="apellidos" placeholder="Apellidos" required><br>
  <input name="dni" placeholder="11111111-Z" required><br>
  <input name="telefono" placeholder="Teléfono (9 dígitos)" required><br>
  <input type="date" name="fecha" required><br>
  <input type="email" name="email" placeholder="Email" required><br>
  <input name="usuario" placeholder="Nombre de usuario" required><br>
  <input type="password" name="password" placeholder="Contraseña" required><br>
  <button id="register_submit" type="submit">Registrar</button>
</form>
<script src="js/validation.js"></script>

>>>>>>> Stashed changes
