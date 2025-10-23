<?php
require 'connect.php';

$mensaje="";


if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (empty($_POST["titulo"]) || empty($_POST["descripcion"]) || empty($_POST["categoria"]) || empty($_POST["fecha"]) || empty($_POST["precio"])) {
    $mensaje="Todos los campos son obligatorios.";
  }else{
    $stmt = $conn->prepare("INSERT INTO items (titulo, descripcion, categoria, fecha, precio) VALUES (?,?,?,?,?)");
    $stmt->bind_param("ssssd", $_POST["titulo"], $_POST["descripcion"], $_POST["categoria"], $_POST["fecha"], $_POST["precio"]);
    $stmt->execute();
    echo "<p>Item añadido correctamnte!✅ <a href='/items'>Volver</a></p>";
  }
}
?>
<h2>Añadir nuevo Item</h2>
<?php if ($mensaje): ?>
    <div class="alert"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>
<form id="item_add_form" method="POST">
  <input name="titulo" placeholder="Título"><br>
  <input name="descripcion" placeholder="Descripción"><br>
  <input name="categoria" placeholder="Categoría"><br>
  <input type="date" name="fecha"><br>
  <input type="number" step="0.01" name="precio" placeholder="Precio"><br>
  <button id="item_add_submit">Guardar</button>
</form>

