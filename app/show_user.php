<?php
// /show_user?user={id}
session_start();

$servername = "db";
$username   = "admin";
$password   = "test";
$dbname     = "database";

$userId = isset($_GET["user"]) ? intval($_GET["user"]) : 0;
if ($userId <= 0) { http_response_code(400); die("Parámetro 'user' inválido."); }

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("DB error: " . $conn->connect_error); }

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
  <title>Perfil de usuario</title>
  <link rel="stylesheet" type="text/css" href="inicioStyle.css">
</head>
<body class="show-user">
  <h1>Perfil de usuario</h1>

  <p><strong>ID:</strong> <?= htmlspecialchars($user["idU"]) ?></p>
  <p><strong>Usuario:</strong> <?= htmlspecialchars($user["usuario"]) ?></p>
  <p><strong>Nombre:</strong> <?= htmlspecialchars($user["nombre"]) ?></p>
  <p><strong>Apellido:</strong> <?= htmlspecialchars($user["apellido"]) ?></p>
  <p><strong>DNI:</strong> <?= htmlspecialchars($user["numDni"] . "-" . $user["letraDni"]) ?></p>
  <p><strong>Teléfono:</strong> <?= htmlspecialchars($user["tlfn"]) ?></p>
  <p><strong>Fecha de nacimiento:</strong> <?= htmlspecialchars($user["fNacimiento"]) ?></p>
  <p><strong>Email:</strong> <?= htmlspecialchars($user["email"]) ?></p>

  <p>
    <a href="modify_user.php?user=<?= urlencode($user['idU']) ?>">Modificar</a> |
    <a href="items.php">Volver</a>
    <form method="post" action="logout.php" style="display:inline;">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <button class="cerrarS"type="submit">Cerrar sesión</button>
    </form>
  </p>
</body>
</html>
