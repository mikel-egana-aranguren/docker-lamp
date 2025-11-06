<?php
// /modify_user?user={id}
require_once 'session_config.php';

$servername = "db";
$username   = "admin";
$password   = "test";
$dbname     = "database";

$userId = isset($_GET["user"]) ? intval($_GET["user"]) : 0;
if ($userId <= 0) { http_response_code(400); die("Parámetro 'user' inválido."); }

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("DB error: " . $conn->connect_error); }

$mensaje = "";

/* Si envían el formulario -> UPDATE */
if (isset($_POST["user_modify_submit"])) {
  // validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Error: CSRF token inválido.');
    }
  // Recibir campos (mismos nombres que en el form)
  $nombre      = trim($_POST["nombre"] ?? "");
  $apellido    = trim($_POST["apellido"] ?? "");
  $numDni      = trim($_POST["numDni"] ?? "");
  $letraDni    = strtoupper(trim($_POST["letraDni"] ?? ""));
  $tlfn        = trim($_POST["tlfn"] ?? "");
  $fNacimiento = trim($_POST["fNacimiento"] ?? "");
  $email       = trim($_POST["email"] ?? "");


  if ($nombre==="" || $apellido==="" || $numDni==="" || $letraDni==="" || $tlfn==="" || $fNacimiento==="" || $email==="") 
  {
    $mensaje = "Faltan campos obligatorios.";
  } 
  else 
  {
    if($conn->query(

      "UPDATE usuarios
       SET nombre='$nombre', apellido='$apellido', numDni='$numDni', letraDni='$letraDni', tlfn='$tlfn', fNacimiento='$fNacimiento', email='$email'
       WHERE idU='$userId'"

    ))
    {
      echo "<script>
              alert('Datos actualizados correctamente');
              window.location.href='show_user.php?user=".$userId."';
            </script>";
      $conn->close();
      exit();
    }
    else
    {
      $mensaje = "Error al actualizar: " . $conn->error;
    }
  }


  /* //Mas seguridad para otra entrega
  // Validaciones mínimas (servidor)
  if ($nombre==="" || $apellido==="" || $numDni==="" || $letraDni==="" || $tlfn==="" || $fNacimiento==="" || $email==="") {
    $mensaje = "Faltan campos obligatorios.";
  } else {
    $stmt = $conn->prepare(
      "UPDATE usuarios
       SET nombre=?, apellido=?, numDni=?, letraDni=?, tlfn=?, fNacimiento=?, email=?
       WHERE idU=?"
    );
    $stmt->bind_param("sssssssi", $nombre, $apellido, $numDni, $letraDni, $tlfn, $fNacimiento, $email, $userId);

    if ($stmt->execute()) {
      // Éxito: alert + volver al perfil
      echo "<script>
              alert('Datos actualizados correctamente');
              window.location.href='show_user.php?user=".$userId."';
            </script>";
      $stmt->close(); $conn->close();
      exit;
    } else {
      $mensaje = "Error al actualizar: " . $conn->error;
    }
    $stmt->close();
  }
    */
}



/* Obtener datos actuales para pre-rellenar el formulario */
$stmt = $conn->prepare(
  "SELECT idU, usuario, nombre, apellido, numDni, letraDni, tlfn, fNacimiento, email
   FROM usuarios WHERE idU = ?"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$res  = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();
$conn->close();

if (!$user) { http_response_code(404); die("Usuario no encontrado."); }
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Modificar usuario</title>
  <link rel="stylesheet" type="text/css" href="inicioStyle.css">
  <script src="js/validar_user_modify.js"></script>
</head>
<body class="modify_user">
  <h1>Modificar usuario</h1>

  <?php if ($mensaje): ?>
    <p style="color:red;"><?= htmlspecialchars($mensaje) ?></p>
  <?php endif; ?>

  <form class="modUser"id="user_modify_form" name="user_modify_form" method="post">
    <p>Usuario (solo lectura): <strong><?= htmlspecialchars($user["usuario"]) ?></strong></p>

    Nombre:<br>
    <input type="text" name="nombre" value="<?= htmlspecialchars($user["nombre"]) ?>" required><br>

    Apellido:<br>
    <input type="text" name="apellido" value="<?= htmlspecialchars($user["apellido"]) ?>" required><br>

    DNI:<br>
    <input type="text" name="numDni" value="<?= htmlspecialchars($user["numDni"]) ?>" required>
    <input type="text" name="letraDni" value="<?= htmlspecialchars($user["letraDni"]) ?>" required><br>

    Teléfono:<br>
    <input type="text" name="tlfn" value="<?= htmlspecialchars($user["tlfn"]) ?>" required><br>

    Fecha de nacimiento:<br>
    <input type="date" name="fNacimiento" value="<?= htmlspecialchars($user["fNacimiento"]) ?>" required><br>

    Email:<br>
    <input type="text" name="email" value="<?= htmlspecialchars($user["email"]) ?>" required><br><br>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
    <button class="guardar_modUser" id="user_modify_submit" name="user_modify_submit" type="submit">Guardar cambios</button>
  </form>

  <p><a href="show_user.php?user=<?= urlencode($user['idU']) ?>">Volver</a></p>
</body>
</html>
