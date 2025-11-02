<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

$message = "";
$message_color = "red";

// Iniciar sesión (Necesario para el token)
session_start();

// Crear token CSRF si no existe
if (!isset($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Conexión con la base de datos
$conexion = new mysqli($hostname, $username, $password, $dbname);
if ($conexion->connect_error) {
    $message = "Error de conexión a la base de datos: " . $conexion->connect_error;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $passwd = $_POST['passwd'] ?? '';

        // Preparar consulta para buscar usuario
        $stmt = $conexion->prepare("SELECT contrasena, user FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $message = "Correo no registrado.";
        } else {
            $stmt->bind_result($db_pass, $user);
            $stmt->fetch();
            if (password_verify($passwd, $db_pass)) {
                session_regenerate_id(true);
                $_SESSION['usuario'] = $user;
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $message = "Login correcto. Redirigiendo...";
                $message_color = "green";
                header("Location: items.php");
                exit;
            } else { 
                $message = "Contraseña incorrecta."; 
            }    
        }
        $stmt->close();
    }
}
// Se cierra la conexión con la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="js/index.js" defer></script>
    <script src="js/login.js" defer></script>
</head>
<body>

<div class="bar">
    <div class="volver_button">
        <a href="index.php" title="Volver al inicio">
            <i class="fa-solid fa-house"></i>
        </a>
    </div>
    <h1>INICIAR SESIÓN</h1>
</div>

<div class="container">
    <div class="content">

        <?php if ($message !== ""): ?>
            <p style="color: <?= $message_color ?>; font-weight:bold; margin-bottom:15px;">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <div class="rellenar">
            <form id="login_form" action="" method="post" class="labels">
                <label for="email">Correo (usuario@servidor.extension)</label>
                <input type="text" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

                <label for="passwd">Contraseña</label>
                <input type="password" id="passwd" name="passwd" required>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <button type="submit" id="login_submit">Confirmar</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

