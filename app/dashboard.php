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

$query = "SELECT nombre, apellidos FROM usuarios WHERE dni = '$dni'";

$result2 = mysqli_query($conn, $query);

if ($result2) {
    $usuario = mysqli_fetch_assoc($result2);
} else {
    echo ("No se ha podido obtener los valores del usuario");
    header('Location: dashboard.php?error=modify_asignatura_failed');
    exit;
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

    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <div class="container-perfil">
        <span class="perfil">
            <?php echo $usuario['nombre']; ?>
            <?php echo $usuario['apellidos']; ?>
        </span>
    </div>

    <form action="logout.php">
        <button class="cerrar-sesion">Cerrar Sesion</button>
    </form>
    <button class="edit-usuario" onclick="window.location.href='editar_usuario.php'">Editar Usuario</button>
    <button class="add-asignatura" onclick="window.location.href='add_asignatura.html'">Añadir Asignatura</button>
    <div class="asignaturas-list">


        <?php foreach ($asignaturas as $asignatura): ?>
            <div class="container-asignatura">

                <span class="asignatura-name">
                    <?php echo htmlspecialchars($asignatura['nombre']); ?>
                </span>
                <span class="asignatura-description">
                    <?php echo htmlspecialchars($asignatura['descripcion']); ?>
                </span>

                <span class="text-creditos">Creditos:</span>
                <div class="container-creditos">
                    <span class="asignatura-creditos">
                        <?php echo htmlspecialchars($asignatura['creditos']); ?>
                    </span>
                </div>

                <span class="text-convocatorias">Convocatorias usadas:</span>
                <div class="container-convocatorias">
                    <span class="asignatura-convocatorias">
                        <?php echo htmlspecialchars($asignatura['convocatorias_usadas']); ?>
                    </span>
                </div>


                <span class="asignatura-actions">

                    <form action="edit_asignatura.php" method="post" class="edit_asignatura">

                        <input type="hidden" name="asignatura_id" value="<?php echo $asignatura['id']; ?>">
                        <button type="submit" class="edit-asignatura">
                            <img class="edit-icon" src="edit.png">
                        </button>

                    </form>




                    <form action="delete_asignatura.php" method="post" class="delete-form" onsubmit="return confirm('¿Seguro que quieres eliminar esta asignatura?');">
                        <input type="hidden" name="asignatura_id" value="<?php echo $asignatura['id']; ?>">
                        <button type="submit" class="delete-asignatura"></button>
                    </form>
                </span>


            </div>
        <?php endforeach; ?>
    </div>


    <script src="script.js"></script>
</body>

</html>
