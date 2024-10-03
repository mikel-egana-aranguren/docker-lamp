<?php
require 'conexion.php';

$sql = "SELECT id, titulo, artista FROM items";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    echo "<a href='show_item.php?item={$row['id']}'>{$row['titulo']} - {$row['artista']}</a><br>";
}
?>
