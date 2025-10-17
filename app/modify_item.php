<?php
require 'connect.php';
$id = $_GET["item"];
$res = $conn->query("SELECT * FROM items WHERE id=$id");
$item = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $conn->prepare("UPDATE items SET titulo=?, descripcion=?, categoria=?, fecha=?, precio=? WHERE id=?");
    $stmt->bind_param("ssssdi", $_POST["titulo"], $_POST["descripcion"], $_POST["categoria"], $_POST["fecha"], $_POST["precio"], $id);
    $stmt->execute();
    echo "<p>Item modificado ✅ <a href='/items'>Volver</a></p>";
}
?>
<form id="item_modify_form" method="POST">
  <input name="titulo" value="<?= $item['titulo'] ?>"><br>
  <input name="descripcion" value="<?= $item['descripcion'] ?>"><br>
  <input name="categoria" value="<?= $item['categoria'] ?>"><br>
  <input type="date" name="fecha" value="<?= $item['fecha'] ?>"><br>
  <input type="number" step="0.01" name="precio" value="<?= $item['precio'] ?>"><br>
  <button id="item_modify_submit">Actualizar</button>
</form>

