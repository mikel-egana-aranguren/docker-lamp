<?php
session_start();
if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header('Location: index.html');
    exit;
}

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$dni = $_SESSION['dniUsuario'];

$query = "SELECT * FROM asignaturas WHERE dni = '$dni'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$asignaturas = array();
while ($row = mysqli_fetch_assoc($result)) {
    $asignaturas[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Asignaturas</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Resto de tu código HTML -->

    <div class="asignaturas-list">
    <button class="add-asignatura" onclick="window.location.href='add_asignatura.html'">Añadir</button>

        <?php foreach ($asignaturas as $asignatura): ?>
            <div class="asignatura-item">
                <span class="asignatura-name"><?php echo htmlspecialchars($asignatura['nombre']); ?></span>
                <span class="asignatura-name"><?php echo htmlspecialchars($asignatura['descripcion']); ?></span>
                <img src="data:image/png;base64,<?php echo base64_encode($asignatura['imagen']); ?>" alt="Imagen de la asignatura" />
                <span class="asignatura-actions">
                    <button class="edit-asignatura" onclick="window.location.href='edit_asignatura.html?id=<?php echo $asignatura['id']; ?>'">Editar</button>

                     
                    <form action="delete_asignatura.php" method="post" class="delete-form">
                        <input type="hidden" name="asignatura_id" value="<?php echo $asignatura['id']; ?>">
                        <button type="submit" class="delete-asignatura">Eliminar</button>
                    </form>
                </span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Resto de tu código HTML -->

    <script src="script.js"></script>
</body>
</html>
