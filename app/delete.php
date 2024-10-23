<?php
include 'databaseConnect.php';
if (isset($_GET['titulu']) && isset($_GET['egilea'])) {
    $titulu = $_GET['titulu'];
    $egilea = $_GET['egilea'];
    if (isset($_POST['confirm'])) {
        $query = "DELETE FROM videojuegos WHERE titulu = ? AND egilea = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $titulu, $egilea);
        if ($stmt->execute()) {
            echo "Videojuego eliminado correctamente.";
        } else {
            echo "Error al eliminar el videojuego.";
        }
        $stmt->close();
        $conn->close();
        header("Location: index.php");
        exit();
    } elseif (isset($_POST['cancel'])) {
        header("Location: index.php");
        exit();
    }
} else {
    echo "Titulu o egilea de videojuego no proporcionado.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Videojuego</title>
</head>
<body>
    <h1>¿Está seguro de que desea eliminar este videojuego?</h1>
    <form method="post">
        <button type="submit" name="confirm">Sí, eliminar</button>
        <button type="submit" name="cancel">No, cancelar</button>
    </form>
</body>
</html>