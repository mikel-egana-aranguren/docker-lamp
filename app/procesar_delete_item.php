<?php
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM items WHERE id='$item_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Ítem eliminado correctamente.";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
