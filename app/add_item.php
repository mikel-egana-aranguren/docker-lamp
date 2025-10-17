<?php
require 'connect.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("INSERT INTO items (titulo, descripcion, categoria, fecha, precio) VALUES (?,?,?,?,?)");
    $stmt->bind_param("sss sd", $_POST["titulo"], $_POST["descripcion"], $_POST["categoria"], $_POST["fecha"], $_POST["precio"]);
    $stmt->execute();
    echo "<p>Item añadido ✅ <a href='/items'>Volver</a></p>";
}
?>
<form id="item_add_form" method="POST">
  <input name="titulo" placeholder="Título"><br>
  <input name="descripcion" placeholder="Descripción"><br>
  <input name="categoria" placeholder="Categoría"><br>
  <input type="date" name="fecha"><br>
  <input type="number" step="0.01" name="precio" placeholder="Precio"><br>
  <button id="item_add_submit">Guardar</button>
</form>

