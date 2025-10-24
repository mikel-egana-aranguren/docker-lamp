<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Usuario logeado
$usuario = $_SESSION['usuario'];

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

// Conexión con la base de datos
$conn = @new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener items
$sql = "SELECT * FROM item";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Coches</title>
    <link rel="stylesheet" href="css/items.css">
    <script src="js/items.js" defer></script>
</head>
<body>

<div class="user-bar">
    <a href="add_item.php"><button>Añadir Coche</button></a>
    <div class="user-dropdown">
        <button class="user-button"><?= htmlspecialchars($usuario) ?> ▼</button>
        <div class="user-dropdown-content">
            <a href="show_user.php?user=<?= urlencode($usuario) ?>">Ver Usuario</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="content">
        <h1>LISTA DE COCHES</h1>
        <table cellpadding="10" cellspacing="0" border="1">
            <tr>
                <th>Nombre</th>
                <th>Año</th>
                <th>Acciones</th>
            </tr>

	    <!-- Imprimimos los nombres de los items y su año de creación. Asimismo, los botones para Ver, Modificar y Eliminar -->
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['nombre']) ?></td>
                        <td><?= htmlspecialchars($row['año']) ?></td>
                        <td>
                            <a href="show_item.php?item=<?= urlencode($row['nombre']) ?>"><button>Ver</button></a>
                            <a href="modify_item.php?item=<?= urlencode($row['nombre']) ?>"><button>Modificar</button></a>
                            <a href="delete_item.php?item=<?= urlencode($row['nombre']) ?>"><button>Eliminar</button></a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No hay coches disponibles</td>
                </tr>
            <?php endif; ?>

        </table>
    </div>
</div>

</body>
</html>

<?php
// Se cierra la conexión con la base de datos
$conn->close();
?>

