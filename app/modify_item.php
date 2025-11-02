<?php

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Conexión con la base de datos
$conexion = new mysqli($hostname, $username, $password, $db);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Se obtiene el item al que hacemos referencia
$nombre = isset($_GET['item']) ? $_GET['item'] : '';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error de seguridad: token CSRF inválido.");
    }
    $nombre_nuevo = $conexion->real_escape_string($_POST['nombre']);
    $año = intval($_POST['anio']);
    $combustible = $conexion->real_escape_string($_POST['combustible']);
    $caballos = intval($_POST['caballos']);
    $precio = floatval($_POST['precio']);

    // Verificar si el nuevo nombre ya existe y pertenece a otro registro
    $stmt_check = $conexion->prepare("SELECT id FROM item WHERE nombre = ? AND nombre != ?");
    $stmt_check->bind_param("ss", $nombre_nuevo, $nombre);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    if ($result_check->num_rows > 0) {
        $message = "<p style='color:red;'>Ya existe un coche con el nombre '{$nombre_nuevo}'.</p>";
    } else {
        // UPDATE incluyendo el nombre
        $stmt = $conexion->prepare("UPDATE item SET nombre = ?, anio = ?, combustible = ?, caballos = ?, precio = ? WHERE nombre = ?");
        $stmt->bind_param("sisids", $nombre_nuevo, $año, $combustible, $caballos, $precio, $nombre);
        
        if ($stmt->execute()) {
            header("Location: items.php");
            exit;
        } else {
            $message = "<p style='color:red;'>Error al actualizar el coche: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $stmt_check->close();
}

// Obtener datos del ítem
$sql = "SELECT * FROM item WHERE nombre = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $nombre);
$stmt->execute();
$result = $stmt->get_result();
$item = $result ? $result->fetch_assoc() : null;
$stmt->close();
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar <?= htmlspecialchars($nombre) ?></title>
    <link rel="stylesheet" href="css/modify_item.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="js/modify_item.js" defer></script>
</head>
<body>

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
            <label>Nombre (Marca + Modelo)</label><br>
            <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($item['nombre']) ?>" required><br><br>

            <label>Año (>=1886)</label><br>
            <input type="number" name="anio" id="anio" value="<?= htmlspecialchars($item['anio']) ?>" min="1886" max="9999" required><br><br>

            <label>Combustible</label><br>
            <input type="text" name="combustible" value="<?= htmlspecialchars($item['combustible']) ?>" required><br><br>

            <label>Caballos (1-2000)</label><br>
            <input type="number" name="caballos" id="caballos" value="<?= htmlspecialchars($item['caballos']) ?>" min="1" max="2000" required><br><br>

            <label>Precio (Máx 12 cifras)</label><br>
            <input type="number" name="precio" id="precio" value="<?= htmlspecialchars($item['precio']) ?>" min="1" max="999999999999" required><br><br>
	    
	    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
	    
            <div class="buttons">
                <button type="submit" id="item_modify_submit">Guardar cambios</button>
            </div>
        </form>
        <?php else: ?>
            <p>Coche no encontrado.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>

