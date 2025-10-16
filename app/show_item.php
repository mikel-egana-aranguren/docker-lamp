<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = isset($_GET['nombre']) ? intval($_GET['nombre']) : 0;
$sql = "SELECT * FROM item WHERE nombre = $nombre";
$result = $conn->query($sql);
$item = $result->fetch_assoc();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mostrar Ítem</title>
</head>
<body>
  <h1>Detalles del Ítem</h1>
  <?php if ($item): ?>
    <p><strong>ID:</strong> <?= htmlspecialchars($item['id']) ?></p>
    <p><strong>Nombre:</strong> <?= htmlspecialchars($item['nombre']) ?></p>
    <p><strong>Descripción:</strong> <?= htmlspecialchars($item['descripcion']) ?></p>

    <a href="modify_item.php?id=<?= $item['id'] ?>"><button>Modificar</button></a>
    <a href="items.php"><button>Volver</button></a>
  <?php else: ?>
    <p>Ítem no encontrado.</p>
    <a href="items.php"><button>Volver</button></a>
  <?php endif; ?>
</body>
</html>
