<?php
// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";
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
