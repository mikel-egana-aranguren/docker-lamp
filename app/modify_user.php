<?php
// modify_user.php (adaptado a la tabla `usuario` y columnas de show_user.php)
ini_set('display_errors', 1);
error_reporting(E_ALL);

$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

function prepare_or_die($cn, $sql, $ctx = '') {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        die("Error en prepare() {$ctx}: " . mysqli_error($cn) . " — SQL: {$sql}");
    }
    return $stmt;
}

// Obtener clave de búsqueda
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;
if ($userKey !== '') {
    // Detectar tipo: email, teléfono (9 dígitos), DNI (formato 12345678-X) o nombre
    if (filter_var($userKey, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE correo = ?";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por correo');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } elseif (preg_match('/^\d{9}$/', $userKey)) {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE telefono = ?";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por telefono');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } elseif (preg_match('/^\d{8}-?[A-Za-z]$/', $userKey)) { // DNI con o sin dash
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE dni = ?";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por dni');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } else {
        // Buscar por nombre (puede devolver muchos; tomamos el primero). Mejor: mostrar lista.
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE nombre = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por nombre');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
    }

    // Obtener resultado (con fallback)
    if (function_exists('mysqli_stmt_get_result')) {
        $res = mysqli_stmt_get_result($stmt);
        if ($res === false) {
            die("mysqli_stmt_get_result() falló: " . mysqli_error($cn));
        }
        $user = mysqli_fetch_assoc($res);
    } else {
        mysqli_stmt_bind_result($stmt, $dni, $nombre, $apellidos, $correo, $contrasena, $telefono, $fecha_nacimiento);
        if (mysqli_stmt_fetch($stmt)) {
            $user = [
                'dni' => $dni,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'correo' => $correo,
                'contrasena' => $contrasena,
                'telefono' => $telefono,
                'fecha_nacimiento' => $fecha_nacimiento
            ];
        } else {
            $user = null;
        }
    }
    mysqli_stmt_close($stmt);
}

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}

// Procesar POST de modificación
$successMsg = "";
$errorMsg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Esperamos que los name del form coincidan con los aquí usados: dni,nombre,apellidos,correo,telefono,fecha_nacimiento,contrasena (opcional)
    $dni_post    = trim($_POST['dni'] ?? '');
    $nombre_post = trim($_POST['nombre'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $correo      = trim($_POST['correo'] ?? '');
    $telefono    = trim($_POST['telefono'] ?? '');
    $fecha_nac   = trim($_POST['fecha_nacimiento'] ?? '');
    $passwd      = $_POST['contrasena'] ?? '';
    $passwd_r    = $_POST['contrasena_repeat'] ?? '';

    // Validaciones (ajusta según tus reglas)
    $nombreOk = (bool) preg_match('/^[A-Za-zÀ-ÿ ]+$/', $nombre_post);
    $apelsOk  = (bool) preg_match('/^[A-Za-zÀ-ÿ ]+$/', $apellidos);
    $emailOk  = (bool) filter_var($correo, FILTER_VALIDATE_EMAIL);
    $tlfOk    = (bool) preg_match('/^\d{9}$/', $telefono);
    $fechaOk  = (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha_nac);

    $dniOk = false;
    if (preg_match('/^(\d{8})-?([A-Za-z])$/', $dni_post, $m)) {
        $num   = (int)$m[1];
        $letra = strtoupper($m[2]);
        $tabla = "TRWAGMYFPDXBNJZSQVHLCKE";
        $dniOk = (isset($tabla[$num % 23]) && $tabla[$num % 23] === $letra);
    }

    if (!$nombreOk || !$apelsOk || !$emailOk || !$tlfOk || !$fechaOk || !$dniOk) {
        $errorMsg = "Algún campo no cumple el formato.";
    } else {
        if ($passwd !== '') {
            if ($passwd !== $passwd_r) {
                $errorMsg = "Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $sql = "UPDATE `usuario` SET `nombre` = ?, `apellidos` = ?, `dni` = ?, `correo` = ?, `telefono` = ?, `fecha_nacimiento` = ?, `contrasena` = ? WHERE `dni` = ?";
                $stmt = prepare_or_die($cn, $sql, 'UPDATE con contrasena');
                mysqli_stmt_bind_param($stmt, "ssssssss", $nombre_post, $apellidos, $dni_post, $correo, $telefono, $fecha_nac, $hash, $dni_post);
                if (!mysqli_stmt_execute($stmt)) {
                    die("Error ejecutando UPDATE: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
                $successMsg = "Datos actualizados (contraseña incluida).";
            }
        } else {
            $sql = "UPDATE `usuario` SET `nombre` = ?, `apellidos` = ?, `dni` = ?, `correo` = ?, `telefono` = ?, `fecha_nacimiento` = ? WHERE `dni` = ?";
            $stmt = prepare_or_die($cn, $sql, 'UPDATE sin contrasena');
            mysqli_stmt_bind_param($stmt, "sssssss", $nombre_post, $apellidos, $dni_post, $correo, $telefono, $fecha_nac, $dni_post);
            if (!mysqli_stmt_execute($stmt)) {
                die("Error ejecutando UPDATE: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            $successMsg = "Datos actualizados.";
        }

        // Recargar datos actualizados (por dni)
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE dni = ?";
        $stmt = prepare_or_die($cn, $sql, 'SELECT recarga');
        mysqli_stmt_bind_param($stmt, "s", $dni_post);
        mysqli_stmt_execute($stmt);
        if (function_exists('mysqli_stmt_get_result')) {
            $res = mysqli_stmt_get_result($stmt);
            $user = mysqli_fetch_assoc($res);
        } else {
            mysqli_stmt_bind_result($stmt, $dni, $nombre, $apellidos, $correo, $contrasena, $telefono, $fecha_nacimiento);
            if (mysqli_stmt_fetch($stmt)) {
                $user = compact('dni','nombre','apellidos','correo','contrasena','telefono','fecha_nacimiento');
            } else {
                $user = null;
            }
        }
        mysqli_stmt_close($stmt);
    }
}

// Mostrar formulario (ejemplo simple). Asegúrate de que los name coinciden con lo que procesa el POST arriba.
?>
<link rel="stylesheet" href="css/modify_user.css">
<div class="container">
  <div class="content">
    <h1>MODIFICAR USUARIO</h1>
    <?php if ($errorMsg): ?>
      <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>
    <?php if ($successMsg): ?>
      <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <div class="rellenar">
      <form id="user_modify_form" action="modify_user.php?user=<?= urlencode($user['dni']) ?>" method="post" class="labels">
        <label for="dni">DNI *</label>
        <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($user['dni']) ?>" required>

        <label for="nombre">Nombre *</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

        <label for="apellidos">Apellidos *</label>
        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($user['apellidos']) ?>" required>

        <label for="correo">Correo *</label>
        <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($user['correo']) ?>" required>

        <label for="telefono">Teléfono *</label>
        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($user['telefono']) ?>" required>

        <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($user['fecha_nacimiento']) ?>" required>

        <details>
          <summary>Cambiar contraseña (opcional)</summary>
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena">
          <label for="contrasena_repeat">Repetir Contraseña</label>
          <input type="password" id="contrasena_repeat" name="contrasena_repeat">
        </details>

        <button type="submit" id="user_modify_submit">Guardar cambios</button>
      </form>
    </div>
  </div>
</div>


