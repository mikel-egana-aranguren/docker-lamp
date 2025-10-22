<?php
session_start();

//PARÁMETROS DE CONEXIÓN A LA BD
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

//OBTENER EL ID DE LA PELÍCULA 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pelicula = intval($_GET['id']);
} else {
    die("Error: No se ha especificado un ID de película válido.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Conectarse a la bd
    $conn = new mysqli($hostname, $username, $password, $db);
    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }
    
    $sql = "DELETE FROM pelicula WHERE idPelicula = $id_pelicula";
    
   
    if ($conn->query($sql) === TRUE) {
        $conn->close();
        header("Location: items.php");
        exit();
    } else {
        die("Error al eliminar la película: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Eliminar Película</title>
     <link rel="stylesheet" type="text/css" href="inicioStyle.css">
</head>
<body class="delete-item">
    <div class="confirmation-box">
        <h1>¿Estás segur@?</h1>
        <p>Esta acción no se puede deshacer.</p>

        <div class="buttons-container" style="text-align:center; margin-top:20px;">
            <form method="post" action="delete_item.php?id=<?php echo $id_pelicula; ?>" style="display: inline;">
                <button type="submit" class="yes-button">Sí</button>
            </form>

            <a href="modify_item.php?id=<?php echo $id_pelicula; ?>" class="no-button">No, volver</a>
            <img src="img/gatoBasura.png" alt="Gato en la basura" style="display:block; margin:20px auto; width:150px;">
        </div>
    </div>

</body>
</html>