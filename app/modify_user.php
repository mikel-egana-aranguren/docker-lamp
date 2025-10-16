<?php
// modify_user.php (archivo completo)
// Asegúrate de ajustar las credenciales si son distintas
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

// Helper: prepara la consulta o muere con mensaje claro
function prepare_or_die($cn, $sql, $context = '') {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        die("Error en prepare() {$context}: " . mysqli_error($cn) . " — SQL: {$sql}");
    }
    return $stmt;
}

// Obtener clave de búsqueda (por GET)
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;
$errorMsg = "";
$successMsg = "";

// Si llega user por GET, buscamos el usuario
if ($userKey !== '') {
    // Detectar tipo: email, teléfono (9 dígitos), DNI (8 dígitos+letra) o nombre
    if (filter_var($userKey, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE correo = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por correo');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } elseif (preg_match('/^\+?\d[\d\s\-\(\)]{6,}\d$/', $userKey) && preg_replace('/\D+/', '', $userKey) !== '') {
        // Consideramos que puede ser teléfono (se normalizará luego)
        $tel_norm = preg_replace('/\D+/', '', $userKey);
        // Buscamos por teléfono normalizado o por su forma almacenada (depende de cómo lo guardes)
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE telefono = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por telefono');
        mysqli_stmt_bind_param($stmt, "s", $tel_norm);
    } elseif (preg_match('/^\d{8}-?[A-Za-z]$/', $userKey)) {
        // DNI con o sin guion
        $dni_norm = strtoupper(str_replace('-', '', $userKey));
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE REPLACE(dni,'-','') = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por dni');
        mysqli_stmt_bind_param($stmt, "s", $dni_norm);
    } else {
        // Nombre (puede haber múltiples; tomamos el primero)
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE nombre = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por nombre');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Error al ejecutar SELECT: " . mysqli_stmt_error($stmt));
    }

    // Obtener resultado (compatibilidad con mysqlnd o fallback)
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

// Si no encontramos usuario, mostramos mensaje (pero permitimos entrar al formulario si se quiere)
if (!$user && $userKey !== '') {
    // No hacemos exit; mostramos mensaje más abajo y permitimos cerrar/volver
    $errorMsg = "Usuario no encontrado según la clave proporcionada.";
}

// ------------------ BLOQUE DE PROCESADO DEL FORMULARIO (POST) ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recogemos los campos (los name deben coincidir con los del formulario)
    $dni_post    = trim($_POST['dni'] ?? '');
    $nombre_post = trim($_POST['nombre'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $correo      = trim($_POST['correo'] ?? '');
    $telefono    = trim($_POST['telefono'] ?? '');
    $fecha_nac   = trim($_POST['fecha_nacimiento'] ?? '');
    $passwd      = $_POST['contrasena'] ?? '';
    $passwd_r    = $_POST['contrasena_repeat'] ?? '';

    $errors = [];

    // Normalizar teléfono quitando todo lo que no sea dígito
    $telefono_digits = preg_replace('/\D+/', '', $telefono);

    // 1) DNI: aceptar con o sin guion, validar letra
    if (!preg_match('/^(\d{8})-?([A-Za-z])$/', $dni_post, $m)) {
        $errors[] = "DNI: formato inválido (ej. 12345678-A o 12345678A).";
    } else {
        $num = (int)$m[1];
        $letra = strtoupper($m[2]);
        $tabla = "TRWAGMYFPDXBNJZSQVHLCKE";
        if (!isset($tabla[$num % 23]) || $tabla[$num % 23] !== $letra) {
            $errors[] = "DNI: letra incorrecta para el número proporcionado.";
        } else {
            // Normalizamos a formato 12345678-A (opcional)
            $dni_post = sprintf("%08d-%s", $num, $letra);
        }
    }

    // 2) Nombre
    if ($nombre_post === '' || !preg_match("/^[A-Za-zÀ-ÿ' -]{2,60}$/u", $nombre_post)) {
        $errors[] = "Nombre: usa letras y/o espacios (2-60 caracteres).";
    }

    // 3) Apellidos
    if ($apellidos === '' || !preg_match("/^[A-Za-zÀ-ÿ' -]{2,80}$/u", $apellidos)) {
        $errors[] = "Apellidos: usa letras y/o espacios (2-80 caracteres).";
    }

    // 4) Correo
    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Correo: formato de email inválido.";
    }

    // 5) Teléfono: verificar que, tras normalizar, hay 9 dígitos (ajusta si quieres internacional)
    if (!preg_match('/^\d{9}$/', $telefono_digits)) {
        $errors[] = "Teléfono: debe contener 9 dígitos (se permiten espacios y prefijos +34; serán normalizados).";
    } else {
        $telefono = $telefono_digits; // guardamos la versión normalizada
    }

    // 6) Fecha de nacimiento: validar YYYY-MM-DD
    if ($fecha_nac === '') {
        $errors[] = "Fecha de nacimiento: obligatorio.";
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $fecha_nac);
        if (!($d && $d->format('Y-m-d') === $fecha_nac)) {
            $errors[] = "Fecha de nacimiento: formato inválido (YYYY-MM-DD).";
        }
    }

    // Si hay errores, no ejecutamos el UPDATE
    if (!empty($errors)) {
        $errorMsg = implode(' ', $errors);
    } else {
        // Procesamos la actualización
        if ($passwd !== '') {
            if ($passwd !== $passwd_r) {
                $errorMsg = "Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $sql = "UPDATE `usuario` SET `nombre` = ?, `apellidos` = ?, `dni` = ?, `correo` = ?, `telefono` = ?, `fecha_nacimiento` = ?, `contrasena` = ? WHERE REPLACE(dni,'-','') = REPLACE(?, '-','')";
                $stmt = prepare_or_die($cn, $sql, 'UPDATE con contrasena');
                // Nota: pasamos dni sin guion en el WHERE para evitar discrepancias
                mysqli_stmt_bind_param($stmt, "sssssss", $nombre_post, $apellidos, $dni_post, $correo, $telefono, $fecha_nac, $hash, $dni_post);
                if (!mysqli_stmt_execute($stmt)) {
                    die("Error ejecutando UPDATE: " . mysqli_stmt_error($stmt));
                }
                mysqli_stmt_close($stmt);
                $successMsg = "Datos actualizados (contraseña incluida).";
            }
        } else {
            $sql = "UPDATE `usuario` SET `nombre` = ?, `apellidos` = ?, `dni` = ?, `correo` = ?, `telefono` = ?, `fecha_nacimiento` = ? WHERE REPLACE(dni,'-','') = REPLACE(?, '-','')";
            $stmt = prepare_or_die($cn, $sql, 'UPDATE sin contrasena');
            mysqli_stmt_bind_param($stmt, "sssssss", $nombre_post, $apellidos, $dni_post, $correo, $telefono, $fecha_nac, $dni_post);
            if (!mysqli_stmt_execute($stmt)) {
                die("Error ejecutando UPDATE: " . mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
            $successMsg = "Datos actualizados.";
        }

        // Recargar datos actualizados para mostrarlos en el formulario
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE REPLACE(dni,'-','') = REPLACE(?, '-','') LIMIT 1";
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
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Modificar usuario</title>
  <link rel="stylesheet" href="css/modify_user.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    /* Estilos mínimos por si falta el CSS externo */
    body { font-family: Arial, sans-serif; background:#f5f5f5; margin:0; padding:20px; }
    .container { max-width:800px; margin:0 auto; background:#fff; padding:24px; border-radius:8px; box-shadow:0 6px 20px rgba(0,0,0,0.08); }
    h1 { margin-top:0; }
    .error { background:#ffe6e6; color:#900; padding:10px; border-radius:6px; margin-bottom:12px; }
    .success { background:#e6ffea; color:#0a5; padding:10px; border-radius:6px; margin-bottom:12px; }
    label { display:block; margin-top:12px; font-weight:600; }
    input[type="text"], input[type="email"], input[type="date"], input[type="password"] { width:100%; padding:10px; margin-top:6px; box-sizing:border-box; border:1px solid #ddd; border-radius:6px; }
    button { margin-top:16px; padding:12px 20px; border:none; background:#007bff; color:#fff; border-radius:8px; cursor:pointer; font-size:16px; }
    button:hover { background:#0069d9; }
    details { margin-top:12px; }
  </style>
</head>
<body>
  <div class="container">
    <h1>Modificar usuario</h1>

    <?php if ($errorMsg): ?>
      <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>

    <?php if ($successMsg): ?>
      <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>

    <?php if (!$user): ?>
      <p>Usuario no cargado. Puedes volver a <a href="list_users.php">la lista</a> o comprobar la clave `user` en la URL.</p>
    <?php endif; ?>

    <form method="post" action="modify_user.php?user=<?= urlencode($userKey ?: '') ?>">
      <label for="dni">DNI *</label>
      <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($user['dni'] ?? '') ?>" required>

      <label for="nombre">Nombre *</label>
      <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($user['nombre'] ?? '') ?>" required>

      <label for="apellidos">Apellidos *</label>
      <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($user['apellidos'] ?? '') ?>" required>

      <label for="correo">Correo *</label>
      <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($user['correo'] ?? '') ?>" required>

      <label for="telefono">Teléfono *</label>
      <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($user['telefono'] ?? '') ?>" required>

      <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
      <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($user['fecha_nacimiento'] ?? '') ?>" required>

      <details>
        <summary>Cambiar contraseña (opcional)</summary>
        <label for="contrasena">Contraseña</label>
        <input type="password" id="contrasena" name="contrasena">

        <label for="contrasena_repeat">Repetir Contraseña</label>
        <input type="password" id="contrasena_repeat" name="contrasena_repeat">
      </details>

      <button type="submit">Guardar cambios</button>
    </form>

    <p style="margin-top:18px;">
      <a href="show_user.php?user=<?= urlencode($userKey ?: '') ?>">Ver usuario</a> |
      <a href="list_users.php">Volver a la lista</a>
    </p>
  </div>
</body>
</html>

