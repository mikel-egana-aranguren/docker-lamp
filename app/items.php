<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Si llega aquí, el usuario está logeado:
$usuario = $_SESSION['usuario'];

echo '
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
    <button class="user-button">' . htmlspecialchars($usuario) . ' ▼</button>
    <div class="user-dropdown-content">
        <a href="show_user.php?user=' . urlencode($usuario) . '">Ver Usuario</a>
        <a href="logout.php">Cerrar Sesión</a>
    </div>
</div>

</div>

<div class="container">
  <div class="content">
    <h1>LISTA DE COCHES</h1>
    <table border="1" cellpadding="10" cellspacing="0">
      <tr>
        <th>Nombre</th>
        <th>Año</th>
        <th>Acciones</th>
      </tr>
   </div>
 </div>
';

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = @new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM item";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nombre = $row['nombre'];
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['año']}</td>
                <td>
                    <a href='show_item.php?item={$nombre}'><button>Ver</button></a>
                    <a href='modify_item.php?item={$nombre}'><button>Modificar</button></a>
                    <a href='delete_item.php?item={$nombre}'><button>Eliminar</button></a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No hay coches disponibles</td></tr>";
}

$conn->close();

echo '
    </table>
  </div>
</div>
';
?>
