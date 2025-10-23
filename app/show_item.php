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

<title><?= htmlspecialchars($nombre) ?></title>
<link rel="stylesheet" href="css/show_item.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<div class="bar">
  <div class="volver_button">
    <a href="items.php" title="Volver al inicio">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>
  <h1>DATOS DEL <?= htmlspecialchars($nombre) ?></h1>
  <div class="modificar_button">
  	<a href="modify_item.php?item=<?= htmlspecialchars($item['nombre']) ?>"><button>Modificar</button></a>
  </div>
</div>
<div class="container">
    <div class="content">
        <?php if ($item): ?>
            <p><strong>Año:</strong> <?= htmlspecialchars($item['año']) ?></p>
            <p><strong>Combustible:</strong> <?= htmlspecialchars($item['combustible']) ?></p>
            <p><strong>Caballos:</strong> <?= htmlspecialchars($item['caballos']) ?></p>
            <p><strong>Precio:</strong> <?= $item['precio'] ?>€</p>
        <?php else: ?>
            <p>Coche no encontrado.</p>
        <?php endif; ?>
    </div>
</div>

