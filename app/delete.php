<?php
include 'databaseConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['titulu']) && isset($_POST['egilea'])) {
        $titulu = $_POST['titulu'];
        $egilea = $_POST['egilea'];

        // Verificar si el videojuego existe
        $query = "SELECT * FROM bideojokoa WHERE titulu = ? AND egilea = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $titulu, $egilea);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Videojuego encontrado, mostrar formulario de confirmación
            if (isset($_POST['confirm'])) {
                $deleteQuery = "DELETE FROM bideojokoa WHERE titulu = ? AND egilea = ?";
                $deleteStmt = $conn->prepare($deleteQuery);
                $deleteStmt->bind_param("ss", $titulu, $egilea);
                if ($deleteStmt->execute()) {
                    echo "Videojuego eliminado correctamente.";
                } else {
                    echo "Error al eliminar el videojuego.";
                }
                $deleteStmt->close();
                $conn->close();
                echo "Pertsona honen datuak gorde dira";
                echo"<script>
                    alert('Pertsona honen datuak gorde dira');
                    window.location.href = 'index.php';
                </script>";
            } elseif (isset($_POST['cancel'])) {
                header("Location: index.php");
                exit();
            }
        } else {
            echo "Datos incorrectos. No se encontró ningún videojuego con ese titulu y egilea.";
        }
        $stmt->close();
    } else {
        echo "Titulu o egilea de videojuego no proporcionado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Videojuego</title>
</head>
<body>
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($result) && $result->num_rows > 0): ?>
        <h1>¿Está seguro de que desea eliminar este videojuego?</h1>
        <form method="post">
            <input type="hidden" name="titulu" value="<?php echo htmlspecialchars($titulu); ?>">
            <input type="hidden" name="egilea" value="<?php echo htmlspecialchars($egilea); ?>">
            <button type="submit" name="confirm">Sí, eliminar</button>
            <button type="submit" name="cancel">No, cancelar</button>
        </form>
    <?php else: ?>
        <h1>Eliminar Videojuego</h1>
        <form method="post">
            <label for="titulu">Titulu:</label>
            <input type="text" id="titulu" name="titulu" required>
            <br>
            <label for="egilea">Egilea:</label>
            <input type="text" id="egilea" name="egilea" required>
            <br>
            <button type="submit">Verificar</button>
        </form>
    <?php endif; ?>
</body>
</html>