<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database"; 

// Conexión con la base de datos
$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Se obtiene el usuario al que hacemos referencia
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';
$user = null;

if ($userKey !== '') {
    $sql = "SELECT user, dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento 
            FROM usuario 
            WHERE correo = ? OR dni = ? OR telefono = ? OR user = ?";
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . mysqli_error($cn));
    }
    mysqli_stmt_bind_param($stmt, "ssss", $userKey, $userKey, $userKey, $userKey);
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
    } else {
        die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
    }
}

// Usuario no existente
if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}

// Se cierra la conexión con la base de datos
mysqli_stmt_close($stmt);
mysqli_close($cn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos del Usuario</title>
    <link rel="stylesheet" href="css/show_user.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>
<body>

<div class="bar">
    <div class="volver_button">
        <a href="items.php" title="Volver al inicio">
            <i class="fa-solid fa-house"></i>
        </a>
    </div>
    <h1>DATOS DEL USUARIO</h1>
    <a href="modify_user.php?user=<?= urlencode($user["user"]) ?>">
        <button>Modificar</button>
    </a>
</div>

<div class="container">
    <div class="content">
        <div class="rellenar">
            <p><strong>Usuario:</strong> <?= htmlspecialchars($user["user"]) ?></p>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($user["nombre"]) ?></p>
            <p><strong>Apellidos:</strong> <?= htmlspecialchars($user["apellidos"]) ?></p>
            <p><strong>Correo:</strong> <?= htmlspecialchars($user["correo"]) ?></p>
            <p><strong>DNI:</strong> <?= htmlspecialchars($user["dni"]) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($user["telefono"]) ?></p>
            <p><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($user["fecha_nacimiento"]) ?></p>
        </div>
    </div>
</div>

</body>
</html>
