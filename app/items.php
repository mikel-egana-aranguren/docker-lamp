<?php
require 'connect.php';

$res = $conn->query("SELECT id, titulo, categoria FROM items");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Listado de Juegos</title>
  <link rel="stylesheet" href="css/styles.css">
</head>

<body>
  <div class="container" style="width: 600px;"> 
    
    <div class="header-controls">
        <h2>Lista de Juegos</h2>
        <a href="/add_item" class="button">Añadir Nuevo</a>
    </div>

    <?php
    if ($res->num_rows > 0) {

        while ($r = $res->fetch_assoc()) {
            ?>
            <div class="list-item-entry">
                
                <span class="item-title" style="font-weight: 700; color: #f0f0f0;">
                    <?= htmlspecialchars($r['titulo']) ?> (<?= htmlspecialchars($r['categoria']) ?>)
                </span>
                
                <span style="display: flex; gap: 15px;">
                    <a href='/modify_item?item=<?= htmlspecialchars($r['id']) ?>'>Editar</a>
                    <a href='/delete_item?item=<?= htmlspecialchars($r['id']) ?>'>Eliminar</a>
                </span>
            </div>
            <?php
        }
    } else {
        echo "<p style='color: #aaaaaa;'>No se encontraron juegos en la base de datos.</p>";
    }
    ?>
    
  </div>
</body>
</html>