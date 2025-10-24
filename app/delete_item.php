<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

// Conexión con la base de datos
$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Se obtiene el item al que hacemos referencia
$id = isset($_GET['item']) ? $_GET['item'] : '';

// Se elimina el item de la base de datos
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM item WHERE nombre = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: items.php");
    exit;
}

// Se obtiene la información del item
$stmt = $conn->prepare("SELECT * FROM item WHERE nombre = ?");
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


