<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Conexión con la base de datos
$conexion = mysqli_connect($hostname, $username, $password, $dbname);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Función para consultas SQL, para reutilizar
function prepare_or_die($conexion, $sql, $ctx = '') {
    $stmt = mysqli_prepare($conexion, $sql);
    if (!$stmt) {
        die("Error en prepare() {$ctx}: " . mysqli_error($conexion) . " — SQL: {$sql}");
    }
    return $stmt;
}

// Se obtiene el usuario al que hacemos referencia
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';
$usuario = null;

if ($userKey !== '') {
    $sql = "SELECT * FROM `usuario` WHERE correo = ? OR telefono = ? OR dni = ? OR user = ? LIMIT 1";
    $stmt = prepare_or_die($conexion, $sql, 'SELECT usuario');
    $stmt->bind_param("ssss", $userKey, $userKey, $userKey, $userKey);
    $stmt->execute();
    $res = mysqli_stmt_get_result($stmt);
    $usuario = mysqli_fetch_assoc($res);
    $stmt->close();
}

if ($_SESSION['usuario'] !== $usuario['user']) {
    die("No tienes permisos para modificar este usuario.");
}

// Usuario no existente
if (!$usuario) {
    echo "Usuario no encontrado.";
    exit;
}

$successMsg = "";
$errorMsg = "";
$message_color = "red";

// Procesar envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error de seguridad: token CSRF inválido.");
    }
    $user_post   = $usuario['user'];
    $dni_post    = trim($_POST['dni'] ?? '');
    $nombre_post = trim($_POST['nombre'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $correo      = trim($_POST['correo'] ?? '');
    $telefono    = trim($_POST['telefono'] ?? '');
    $fecha_nac   = trim($_POST['fecha_nacimiento'] ?? '');
    $passwd      = $_POST['contrasena'] ?? '';
    $passwd_r    = $_POST['contrasena_repeat'] ?? '';

    // Comprobación de duplicados
    $dup_sql = "SELECT user, correo, dni, telefono 
                FROM usuario 
                WHERE (correo = ? OR dni = ? OR telefono = ?) AND user <> ?";
    $dup_stmt = prepare_or_die($conexion, $dup_sql, 'SELECT duplicados');
    $dup_stmt->bind_param("ssss", $correo, $dni_post, $telefono, $user_post);
    $dup_stmt->execute();
    $dup_res = mysqli_stmt_get_result($dup_stmt);

    if ($dup_user = mysqli_fetch_assoc($dup_res)) {
        if ($dup_user['correo'] === $correo) {
            $errorMsg = "El correo ya está registrado por otro usuario.";
        } elseif ($dup_user['dni'] === $dni_post) {
            $errorMsg = "El DNI ya está registrado por otro usuario.";
        } elseif ($dup_user['telefono'] === $telefono) {
            $errorMsg = "El teléfono ya está registrado por otro usuario.";
        }
        $dup_stmt->close();
    } else {
        $dup_stmt->close();

        // Actualización (con o sin contraseña)
        if ($passwd !== '') {
            if ($passwd !== $passwd_r) {
                $errorMsg = "Las contraseñas no coinciden.";
            } else {
                $sql = "UPDATE `usuario` 
                        SET `nombre`=?, `apellidos`=?, `dni`=?, `correo`=?, 
                            `telefono`=?, `fecha_nacimiento`=?, `contrasena`=? 
                        WHERE `user`=?";
                $stmt = prepare_or_die($conexion, $sql, 'UPDATE con contrasena');
                
                // Hashear la contraseña (usa BYCRYPT)
                $passwd_hashed = password_hash($passwd, PASSWORD_BCRYPT);
                
                $stmt->bind_param("ssssssss", 
                    $nombre_post, $apellidos, $dni_post, $correo, 
                    $telefono, $fecha_nac, $passwd_hashed, $user_post
                );
                $stmt->execute();
                $stmt->close();
                $successMsg = "Datos actualizados (contraseña incluida).";
                $message_color = "green";
            }
        } else {
            $sql = "UPDATE `usuario` 
                    SET `nombre`=?, `apellidos`=?, `dni`=?, `correo`=?, 
                        `telefono`=?, `fecha_nacimiento`=? 
                    WHERE `user`=?";
            $stmt = prepare_or_die($conexion, $sql, 'UPDATE sin contrasena');
            $stmt->bind_param("sssssss", 
                $nombre_post, $apellidos, $dni_post, $correo, 
                $telefono, $fecha_nac, $user_post
            );
            $stmt->execute();
            $stmt->close();
            $successMsg = "Datos actualizados.";
            $message_color = "green";
        }

        // Recargar datos actualizados
        $sql = "SELECT * FROM `usuario` WHERE user = ?";
        $stmt = prepare_or_die($conexion, $sql, 'SELECT recarga');
        $stmt->bind_param("s", $user_post);
        $stmt->execute();
        $res = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($res);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="css/modify_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="js/modify_user.js" defer></script>
</head>
<body>

<div class="bar">
    <div class="volver_button">
        <a href="show_user.php?user=<?= urlencode($usuario['user']) ?>" title="Volver al inicio">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
    </div>
    <h1>MODIFICAR USUARIO</h1>
</div>

<div class="container">
    <div class="content">
        <?php if ($errorMsg): ?>
            <p style="color: red; font-weight: bold;"><?= htmlspecialchars($errorMsg) ?></p>
        <?php endif; ?>
        <?php if ($successMsg): ?>
            <p style="color: green; font-weight: bold;"><?= htmlspecialchars($successMsg) ?></p>
        <?php endif; ?>

        <div class="rellenar">
            <form id="user_modify_form" action="modify_user.php?user=<?= urlencode($usuario['user']) ?>" method="post" class="labels">
                <div class="readonly-field">
                    <label for="user_display">Usuario</label>
                    <input type="text" id="user_display" value="<?= htmlspecialchars($usuario['user']) ?>" readonly class="input-readonly">
                </div>

                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>

                <label for="correo">Correo (usuario@servidor.extension)</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

                <label for="dni">DNI (Ejemplo: 12345678Z)</label>
                <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>" required>

                <label for="telefono">Teléfono (6, 7 o 9 + 8 dígitos)</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>

                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha_nacimiento']) ?>" required>

                <details>
                    <summary>Cambiar contraseña</summary>
                    <label for="contrasena">Contraseña</label>
                    <input type="password" id="contrasena" name="contrasena">
                    <label for="contrasena_repeat">Repetir Contraseña</label>
                    <input type="password" id="contrasena_repeat" name="contrasena_repeat">
                </details>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <button type="submit" id="user_modify_submit">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

