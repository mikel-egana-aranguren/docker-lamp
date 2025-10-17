<?php
require 'connect.php';
$res = $conn->query("SELECT id, titulo, categoria FROM items");
echo "<h2>Listado de Items</h2><a href='/add_item'>Añadir nuevo</a><br><br>";
while ($r = $res->fetch_assoc()) {
  echo "<div>
    <b>{$r['titulo']}</b> ({$r['categoria']})
    <a href='/modify_item?item={$r['id']}'>Editar</a>
    <a href='/delete_item?item={$r['id']}'>Eliminar</a>
  </div>";
}
?>

