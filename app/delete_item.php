<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Confirmación de eliminación
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: items.php");
    exit;
}

$sql = "SELECT * FROM items WHERE id = $id";
$result = $conn->query($sql);
$item = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eliminar Ítem</title>
</head>
<body>
  <h1>Eliminar Ítem</h1>
  <?php if ($item): ?>
    <p>¿Estás seguro de que deseas eliminar <strong><?= htmlspecialchars($item['nombre']) ?></strong>?</p>
    <form method="POST">
      <button type="submit" id="item_delete_submit">Sí, eliminar</button>
      <button type="button" onclick="window.location.href='items.php'">Cancelar</button>
    </form>
  <?php else: ?>
    <p>Ítem no encontrado.</p>
    <a href="items.php"><button>Volver</button></a>
  <?php endif; ?>
</body>
</html>

