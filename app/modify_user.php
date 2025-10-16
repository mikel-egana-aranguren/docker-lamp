<?php
// modify_user.php (versión corregida y verificada)
// Ajusta credenciales si hace falta
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

// Conexión
$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Helper: preparar la consulta o morir con mensaje claro
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
$warnings = [];

// Si viene user, intentamos cargar al usuario (correo / teléfono / dni / nombre)
if ($userKey !== '') {
    if (filter_var($userKey, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE correo = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por correo');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } elseif (preg_match('/^\+?\d[\d\s\-\(\)]{6,}\d$/', $userKey) && preg_replace('/\D+/', '', $userKey) !== '') {
        $tel_norm = preg_replace('/\D+/', '', $userKey);
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE telefono = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por telefono');
        mysqli_stmt_bind_param($stmt, "s", $tel_norm);
    } elseif (preg_match('/^\d{8}-?[A-Za-z]$/', $userKey)) {
        $dni_norm = strtoupper(str_replace('-', '', $userKey));
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE REPLACE(dni,'-','') = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por dni');
        mysqli_stmt_bind_param($stmt, "s", $dni_norm);
    } else {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE nombre = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por nombre');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    }

    if (!mysqli_stmt_execute($stmt)) {
        die("Error al ejecutar SELECT: " . mysqli_stmt_error($stmt));
    }

    if (function_exists('mysqli_stmt_get_result')) {
        $res = mysqli_stmt_get_result($stmt);
        if ($res !== false) {
            $user = mysqli_fetch_assoc($res);
        }
    } else {
        mysqli_stmt_bind_result($stmt, $dni, $nombre, $apellidos, $correo, $contrasena, $telefono, $fecha_nacimiento);
        if (mysqli_stmt_fetch($stmt)) {
            $user = array(
                'dni' => $dni,
                'nombre' => $nombre,
                'apellidos' => $apellidos,
                'correo' => $correo,
                'contrasena' => $contrasena,
                'telefono' => $telefono,
                'fecha_nacimiento' => $fecha_nacimiento
            );
        }
    }
    mysqli_stmt_close($stmt);
}

if (!$user && $userKey !== '') {
    $errorMsg = "Usuario no encontrado con la clave proporcionada.";
}

// ------------------ PROCESAR POST ------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dni_post    = trim($_POST['dni'] ?? '');
    $nombre_post = trim($_POST['nombre'] ?? '');
    $apellidos   = trim($_POST['apellidos'] ?? '');
    $correo      = trim($_POST['correo'] ?? '');
    $telefono    = trim($_POST['telefono'] ?? '');
    $fecha_nac   = trim($_POST['fecha_nacimiento'] ?? '');
    $passwd      = $_POST['contrasena'] ?? '';
    $passwd_r    = $_POST['contrasena_repeat'] ?? '';

    $errors = array();
    $warnings = array();

    // Normalizar teléfono quitando no dígitos
    $telefono_digits = preg_replace('/\D+/', '', $telefono);

    // 1) DNI: validar formato; si letra incorrecta -> advertencia (no bloqueante)
    if (!preg_match('/^(\d{8})-?([A-Za-z])$/', $dni_post, $m)) {
        $errors[] = "DNI: formato inválido (ej. 12345678-A o 12345678A).";
    } else {
        $num = (int)$m[1];
        $letra = strtoupper($m[2]);
        $tabla = "TRWAGMYFPDXBNJZSQVHLCKE";
        if (!isset($tabla[$num % 23])) {
            $errors[] = "DNI: número inválido.";
        } elseif ($tabla[$num % 23] !== $letra) {
            $warnings[] = "Advertencia: letra del DNI no corresponde con el número proporcionado.";
            $dni_post = sprintf("%08d-%s", $num, $letra);
        } else {
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

    // 5) Teléfono: tras normalizar, 9 dígitos
    if (!preg_match('/^\d{9}$/', $telefono_digits)) {
        $errors[] = "Teléfono: debe contener 9 dígitos (se permiten espacios y prefijos +34; serán normalizados).";
    } else {
        $telefono = $telefono_digits;
    }

    // 6) Fecha de nacimiento
    if ($fecha_nac === '') {
        $errors[] = "Fecha de nacimiento: obligatorio.";
    } else {
        $d = DateTime::createFromFormat('Y-m-d', $fecha_nac);
        if (!($d && $d->format('Y-m-d') === $fecha_nac)) {
            $errors[] = "Fecha de nacimiento: formato inválido (YYYY-MM-DD).";
        }
    }

    // Si hay errores, no hacemos UPDATE
    if (!empty($errors)) {
        $errorMsg = implode(' ', $errors);
    } else {
        // Hacer UPDATE (si passwd no vacía -> actualizar contraseña hasheada)
        if ($passwd !== '') {
            if ($passwd !== $passwd_r) {
                $errorMsg = "Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $sql = "UPDATE `usuario` SET `nombre` = ?, `apellidos` = ?, `dni` = ?, `correo` = ?, `telefono` = ?, `fecha_nacimiento` = ?, `contrasena` = ? WHERE REPLACE(dni,'-','') = REPLACE(?, '-','')";
                $stmt = prepare_or_die($cn, $sql, 'UPDATE con contrasena');
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

        // Recargar datos actualizados
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE REPLACE(dni,'-','') = REPLACE(?, '-','') LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT recarga');
        mysqli_stmt_bind_param($stmt, "s", $dni_post);
        mysqli_stmt_execute($stmt);
        if (function_exists('mysqli_stmt_get_result')) {
            $res = mysqli_stmt_get_result($stmt);
            if ($res !== false) {
                $user = mysqli_fetch_assoc($res);
            }
        } else {
            mysqli_stmt_bind_result($stmt, $dni, $nombre, $apellidos, $correo, $contrasena, $telefono, $fecha_nacimiento);
            if (mysqli_stmt_fetch($stmt)) {
                $user = array(
                    'dni' => $dni,
                    'nombre' => $nombre,
                    'apellidos' => $apellidos,
                    'correo' => $correo,
                    'contrasena' => $contrasena,
                    'telefono' => $telefono,
                    'fecha_nacimiento' => $fecha_nacimiento
                );
            }
        }
        mysqli_stmt_close($stmt);

        // Si hay warnings (por ej. letra DNI), los añadimos al successMsg
        if (!empty($warnings)) {
            $successMsg = trim(($successMsg ?? '') . ' ' . implode(' ', $warnings));

