<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id = isset($_GET['nombre']) ? intval($_GET['nombre']) : 0;

// Si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $stmt = $conn->prepare("UPDATE item SET nombre = ?, descripcion = ? WHERE nombre = ?");
    $stmt->bind_param("ssi", $nombre, $descripcion, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: show_item.php?id=$id");
    exit;
}

// Obtener datos del ítem
$sql = "SELECT * FROM item WHERE nombre = $id";
$result = $conn->query($sql);
$item = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Modificar Ítem</title>
</head>
<body>
  <h1>Modificar Ítem</h1>
  <?php if ($item): ?>
    <form id="item_modify_form" method="POST">
      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($item['nombre']) ?>" required><br><br>
      <label>Descripción:</label>
      <textarea name="descripcion" required><?= htmlspecialchars($item['descripcion']) ?></textarea><br><br>
      <button type="submit" id="item_modify_submit">Guardar cambios</button>
    </form>
  <?php else: ?>
    <p>Ítem no encontrado.</p>
  <?php endif; ?>
  <a href="items.php"><button>Volver</button></a>
</body>
</html>
