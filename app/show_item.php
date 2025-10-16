<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$nombre = isset($_GET['item']) ? $_GET['item'] : '';

// Obtener datos del ítem
$sql = "SELECT * FROM item WHERE nombre = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conn->error);
}
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();
$item = $result ? $result->fetch_assoc() : null;

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/items.css">
    <title>Detalles del ítem</title>
</head>
<body>
<div class="container">
    <div class="content">
        <h1>Detalles del <?= htmlspecialchars($nombre) ?></h1>

        <?php if ($item): ?>
            <p><strong>Año:</strong> <?= htmlspecialchars($item['año']) ?></p>
            <p><strong>Combustible:</strong> <?= htmlspecialchars($item['combustible']) ?></p>
            <p><strong>Caballos:</strong> <?= htmlspecialchars($item['caballos']) ?></p>
            <p><strong>Precio:</strong> <?= $item['precio'] ?>€</p> <!-- NUEVO -->

            <a href="modify_item.php?item=<?= urlencode($item['nombre']) ?>"><button>Modificar</button></a>
            <a href="items.php"><button>Volver</button></a>
        <?php else: ?>
            <p>Coche no encontrado.</p>
            <a href="items.php"><button>Volver</button></a>
        <?php endif; ?>
    </div>
</div>
</body>
</html>

