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
// Si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_nuevo = $conn->real_escape_string($_POST['nombre']);
    $año = intval($_POST['año']);
    $combustible = $conn->real_escape_string($_POST['combustible']);
    $caballos = intval($_POST['caballos']);

    $stmt = $conn->prepare("UPDATE item SET nombre = ?, año = ?, combustible = ?, caballos = ? WHERE nombre = ?");
    $stmt->bind_param("sisis", $nombre_nuevo, $año, $combustible, $caballos, $nombre);
    if ($stmt->execute()) {
     header("Location: items.php");
     exit;
    } else {
     echo "Error al actualizar el coche: " . $stmt->error;
    }
    $stmt->close();
}

// Obtener datos del ítem
$sql = "SELECT * FROM item WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();
$item = $result ? $result->fetch_assoc() : null;
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<link rel="stylesheet" href="css/modify_item.css">
 <div class="container">
    <div class="content">
      <h1>Modificar Coche</h1>
      <?php if ($item): ?>
        <form method="POST" id="item_modify_form">
          <label>Nombre:</label><br>
          <input type="text" name="nombre" value="<?= htmlspecialchars($item['nombre']) ?>" required><br><br>
          <label>Año:</label><br>
          <input type="number" name="año" value="<?= htmlspecialchars($item['año']) ?>" required><br><br>
          <label>Combustible:</label><br>
          <input type="text" name="combustible" value="<?= htmlspecialchars($item['combustible']) ?>" required><br><br>
          <label>Caballos:</label><br>
          <input type="number" name="caballos" value="<?= htmlspecialchars($item['caballos']) ?>" required><br><br>
          <div class="buttons">
          	<button type="submit" id="item_modify_submit">Guardar cambios</button>
          </div>
        </form>
      <?php else: ?>
        <p>Coche no encontrado.</p>
      <?php endif; ?>
      <a href="items.php"><button>Volver</button></a>
    </div>
  </div>
</html>
