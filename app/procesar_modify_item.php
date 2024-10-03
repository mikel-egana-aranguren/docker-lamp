<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];
    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $artista = $_POST['artista'];
    $genero = $_POST['genero'];
    $descripcion = $_POST['descripcion'];

    $sql = "UPDATE items SET titulo='$titulo', anio='$anio', artista='$artista', genero='$genero', descripcion='$descripcion'
            WHERE id='$item_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Ítem modificado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>