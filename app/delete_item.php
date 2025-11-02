<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

session_start();
// Generación de token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Conexión con la base de datos
$conexion = new mysqli($hostname, $username, $password, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Se obtiene el item al que hacemos referencia
$id = isset($_GET['item']) ? $_GET['item'] : '';

// Se elimina el item de la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobación de token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error de seguridad: token CSRF inválido.");
    }
    $stmt = $conexion->prepare("DELETE FROM item WHERE nombre = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: items.php");
    exit;
}

// Se obtiene la información del item
$stmt = $conexion->prepare("SELECT * FROM item WHERE nombre = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result ? $result->fetch_assoc() : null;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Coche</title>
  <link rel="stylesheet" href="css/items.css">
</head>
<body>
  <div class="container">
    <div class="content">
      <h1>Eliminar Coche</h1>
      <table cellpadding="10" cellspacing="0">
        <tr>
          <td>
            <?php if ($item): ?>
              <p>¿Estás seguro de que deseas eliminar <strong><?= htmlspecialchars($item['nombre']) ?></strong>?</p>
              <form method="POST">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <button type="submit" id="item_delete_submit">Confirmar</button>
                <button type="button" onclick="window.location.href='items.php'">Cancelar</button>
              </form>
            <?php else: ?>
              <p>Ítem no encontrado.</p>
              <a href="delete_item.php"><button>Volver</button></a>
            <?php endif; ?>
          </td>
        </tr>
      </table>
    </div>
  </div>
</body>
</html>


