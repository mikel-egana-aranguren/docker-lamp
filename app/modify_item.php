<?php
include 'databaseConnect.php';

// Verificar si el método de la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario usando $_POST
    $titulu = $_POST['titulu'];
    $egilea = $_POST['egilea'];
    $prezioa = $_POST['prezioa'];
    $mota = $_POST['mota'];
    $urtea = $_POST['urtea'];

    // Comprobar si los valores recibidos no están vacíos
    if (empty($titulu) || empty($egilea) || empty($prezioa) || empty($mota) || empty($urtea)) {
        echo "Errorea: Datu guztiak bete behar dira.";
        exit();
    }

    // Verificar que el precio y el año sean numéricos
    if (!is_numeric($prezioa) || !is_numeric($urtea)) {
        echo "Errorea: Prezioa eta urtea balio numerikoak izan behar dira.";
        exit();
    }

    // Consulta para verificar si el videojuego ya existe
    $query = "SELECT * FROM bideojokoa WHERE titulu = ? AND egilea = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo "Errorea prestatzen SELECT kontsulta: " . $conn->error;
        exit();
    }

    // Enlazar los parámetros a la consulta
    $stmt->bind_param("ss", $titulu, $egilea);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si existe el videojuego
    if ($result->num_rows == 1) {
        // Preparar la consulta para actualizar los datos
        $updateQuery = "UPDATE bideojokoa SET prezioa = ?, mota = ?, urtea = ? WHERE titulu = ? AND egilea = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if ($updateStmt === false) {
            echo "Errorea prestatzen UPDATE kontsulta: " . $conn->error;
            exit();
        }

        // Enlazar los parámetros a la consulta
        $updateStmt->bind_param("dssss", $prezioa, $mota, $urtea, $titulu, $egilea);

        // Ejecutar la consulta de actualización
        if ($updateStmt->execute()) {
            // Si la actualización fue exitosa, redirigir a la página principal
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak eguneratzean: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        echo "Errorea: Ez da aurkitu bideojokorik izen horrekin.";
    }

    // Cerrar la consulta
    $stmt->close();
} 

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bideojokoa Aldatu</title>
    <link rel="stylesheet" href="main_style.css">
    <script src="main_script.js"></script>
</head>
<body>
    <h1>Bideojokoa Aldatu</h1>
    <form action="modify_item.php" method="post">
        <label for="titulu">Titulua:</label>
        <input type="text" id="titulu" name="titulu" required><br><br>
        <label for="egilea">Egilea:</label>
        <input type="text" id="egilea" name="egilea" required><br><br>
        <label for="prezioa">Prezioa:</label>
        <input type="number" id="prezioa" name="prezioa" required><br><br>
        <label for="mota">Mota:</label>
        <input type="text" id="mota" name="mota" required><br><br>
        <label for="urtea">Urtea:</label>
        <input type="number" id="urtea" name="urtea" required><br><br>
        <button type="submit">Aldatu</button>
    </form>
    <button onclick="window.location.href='index.php'">Atzera</button>
</body>
</html>
