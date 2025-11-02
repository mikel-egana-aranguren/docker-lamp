<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

// Conexión con la base de datos
$conexion = new mysqli($hostname, $username, $password, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Se obtiene el item al que hacemos referencia
$nombre = isset($_GET['item']) ? $_GET['item'] : '';

// Obtener datos del ítem
$sql = "SELECT * FROM item WHERE nombre = ?";
$stmt = $conexion->prepare($sql);

if (!$stmt) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();
$item = $result ? $result->fetch_assoc() : null;

// Se cierra la conexión con la base de datos
$stmt->close();
$conexion->close();
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
            <p><strong>Año:</strong> <?= htmlspecialchars($item['anio']) ?></p>
            <p><strong>Combustible:</strong> <?= htmlspecialchars($item['combustible']) ?></p>
            <p><strong>Caballos:</strong> <?= htmlspecialchars($item['caballos']) ?></p>
            <p><strong>Precio:</strong> <?= $item['precio'] ?>€</p>
        <?php else: ?>
            <p>Coche no encontrado.</p>
        <?php endif; ?>
    </div>
</div>

