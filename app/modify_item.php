<?php
require 'conexion.php';
$item_id = $_GET['item'];
$sql = "SELECT * FROM items WHERE id='$item_id'";
$item = $conn->query($sql)->fetch_assoc();
?>

<form id="item_modify_form" action="procesar_modify_item.php" method="POST">
    <input type="hidden" name="item_id" value="<?= $item['id'] ?>">
    
    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="titulo" value="<?= $item['titulo'] ?>" required>
    
    <label for="anio">Año:</label>
    <input type="number" id="anio" name="anio" value="<?= $item['anio'] ?>" required>
    
    <label for="artista">Artista:</label>
    <input type="text" id="artista" name="artista" value="<?= $item['artista'] ?>" required>
    
    <label for="genero">Género:</label>
    <input type="text" id="genero" name="genero" value="<?= $item['genero'] ?>" required>
    
    <label for="descripcion">Descripción:</label>
    <textarea id="descripcion" name="descripcion" required><?= $item['descripcion'] ?></textarea>

    <input id="item_modify_submit" type="submit" value="Modificar Ítem">
</form>