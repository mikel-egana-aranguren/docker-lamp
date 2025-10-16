<?php
// --- CONFIGURACIÓN DE LA BASE DE DATOS ---
$hostname = "db";        // o "localhost" si usas XAMPP
$username = "admin";     // tu usuario
$password = "test";      // tu contraseña
$dbname   = "database";  // nombre de la base de datos

// --- CONECTAR A LA BASE DE DATOS ---
$conn = new mysqli($hostname, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("<p style='color:red'>❌ Error de conexión: " . $conn->connect_error . "</p>");
}

// --- SI EL FORMULARIO SE HA ENVIADO ---
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $item_name  = $_POST['item_name'] ?? '';
    $item_desc  = $_POST['item_desc'] ?? '';
    $item_price = $_POST['item_price'] ?? 0;

    if (!empty($item_name) && !empty($item_desc)) {
        $stmt = $conn->prepare("INSERT INTO items (nombre, descripcion, precio) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $item_name, $item_desc, $item_price);

        if ($stmt->execute()) {
            echo "<p style='color:green'>✅ Item añadido correctamente.</p>";
        } else {
            echo "<p style='color:red'>❌ Error al insertar: " . $stmt->error . "</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:orange'>⚠️ Por favor, completa todos los campos.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Item</title>
  <link rel="stylesheet" href="css/add_item.css">
  <script src="js/add_item.js" defer></script>
</head>
<body>
  <div class="container">
    <div class="content">
      <h1>Añadir Item</h1>

      <form id="item_add_form" action="add_item.php" method="post" class="labels">
        <label for="item_name">Nombre del item</label>
        <input type="text" id="item_name" name="item_name" required>

        <label for="item_desc">Descripción</label>
        <input type="text" id="item_desc" name="item_desc" required>

        <label for="item_price">Precio (€)</label>
        <input type="number" id="item_price" name="item_price" min="0" step="0.01" required>

        <button type="submit" id="item_add_submit">Añadir</button>
      </form>
    </div>
  </div>
</body>
</html>

