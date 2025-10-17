<?php
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database"; 


$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}


$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;

if ($userKey !== '') {
    // Buscar en todos los campos relevantes
    $sql = "SELECT user, dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento 
        FROM usuario 
        WHERE correo = ? OR dni = ? OR telefono = ? OR user = ?";
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        die("Error al preparar la consulta: " . mysqli_error($cn));
    }
    // Vincular los 4 parámetros con el mismo valor
    mysqli_stmt_bind_param($stmt, "ssss", $userKey, $userKey, $userKey, $userKey);


    // Ejecutar la consulta
    if (mysqli_stmt_execute($stmt)) {
        $res = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
    } else {
        die("Error al ejecutar la consulta: " . mysqli_stmt_error($stmt));
    }
}

if (!$user) {
    echo "Usuario no encontrado.";
    exit;
}

// Mostrar la información del usuario
echo '
<link rel="stylesheet" href="css/show_user.css">
<div class="container">
  <div class="content">
    <h1>DATOS DEL USUARIO</h1>
    <div class="rellenar">

      <p><strong>Usuario:</strong> '.htmlspecialchars($user["user"]).'</p>
      <p><strong>Nombre:</strong> '.htmlspecialchars($user["nombre"]).'</p>
      <p><strong>Apellidos:</strong> '.htmlspecialchars($user["apellidos"]).'</p>
      <p><strong>Correo:</strong> '.htmlspecialchars($user["correo"]).'</p>
      <p><strong>DNI:</strong> '.htmlspecialchars($user["dni"]).'</p>
      <p><strong>Teléfono:</strong> '.htmlspecialchars($user["telefono"]).'</p>
      <p><strong>Fecha de nacimiento:</strong> '.htmlspecialchars($user["fecha_nacimiento"]).'</p>

    </div>

    <div class="botones">
      <a href="modify_user.php?user='.urlencode($user["user"]).'" class="boton">Modificar</a>
      <a href="items.php" class="boton-sec">Volver</a>
    </div>
  </div>
</div>
';
?>

