<?php
// Mostrar errores (solo para desarrollo, puedes quitarlo después)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

// Procesar datos del formulario si se envió
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $item_name  = $_POST["item_name"];
    $item_desc  = $_POST["item_desc"];
    $item_price = $_POST["item_price"];

    // Aquí podrías añadir la lógica para guardar en BD
    // Por ahora solo mostramos los datos enviados (modo test)
    echo "<p>Item recibido: $item_name | $item_desc | $item_price €</p>";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Añadir Item</title>

  <!-- Enlaces a CSS y JS -->
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
