<?php
require 'connect.php';
$id = $_GET["item"];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $conn->query("DELETE FROM items WHERE id=$id");
    echo "<p>Item eliminado ✅ <a href='/items'>Volver</a></p>";
    exit;
}
?>
<h3>¿Seguro que quieres eliminar este item?</h3>
<form id="item_delete_form" method="POST">
  <button id="item_delete_submit" type="submit">Sí, borrar</button>
  <a href="/items">Cancelar</a>
</form>

