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
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_nuevo = $conn->real_escape_string($_POST['nombre']);
    $año = intval($_POST['año']);
    $combustible = $conn->real_escape_string($_POST['combustible']);
    $caballos = intval($_POST['caballos']);
    $precio = floatval($_POST['precio']);

    // Si no hay mensaje de error, hacemos el UPDATE
    if ($message === '') {
        $stmt = $conn->prepare("UPDATE item SET año = ?, combustible = ?, caballos = ?, precio = ? WHERE nombre = ?");
	$stmt->bind_param("isids", $año, $combustible, $caballos, $precio, $nombre);
        if ($stmt->execute()) {
            header("Location: items.php");
            exit;
        } else {
            $message = "<p style='color:red;'>Error al actualizar el coche: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
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
<title>Modificar <?= htmlspecialchars($nombre) ?></title>
<link rel="stylesheet" href="css/modify_item.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<div class="bar">
  <div class="volver_button">
    <a href="items.php" title="Volver al inicio">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>
  <h1>MODIFICAR <?= htmlspecialchars($nombre) ?></h1>
</div>

<div class="container">
	<div class="content">
	<?= $message ?>
	<?php if ($item): ?>
	<form method="POST" id="item_modify_form">

	  <div class="readonly-field">
	  	<label>Nombre</label><br>
	  	<input type="text" name="nombre" value="<?= htmlspecialchars($item['nombre']) ?>" readonly class="input-readonly"><br><br>
	  </div>
	  
	  <label>Año (>=1886)</label><br>
	  <input type="number" name="año" id="año" value="<?= htmlspecialchars($item['año']) ?>" min="1886" max="9999" required><br><br>
	  
	  <label>Combustible</label><br>
	  <input type="text" name="combustible" value="<?= htmlspecialchars($item['combustible']) ?>" required><br><br>
	  
	  <label>Caballos (1-2000)</label><br>
	  <input type="number" name="caballos" id="caballos" value="<?= htmlspecialchars($item['caballos']) ?>" min="1" max="2000" required><br><br>
	  
	  <label>Precio (Máx 12 cifras)</label><br>
	  <input type="number" name="precio" id="precio" value="<?= htmlspecialchars($item['precio']) ?>" min="1" max="999999999999" required><br><br>
	  
	  <div class="buttons">
	    <button type="submit" id="item_modify_submit">Guardar cambios</button>
	  </div>
	</form>
	<?php else: ?>
		<p>Coche no encontrado.</p>
	<?php endif; ?>
	</div>
</div>


