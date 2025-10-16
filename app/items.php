<?php

session_start();

if (!isset($_SESSION['username'])) {
    // Si no hay usuario logueado, redirigimos al login o mostramos mensaje
    header("Location: login.php");
    exit;
}

$username_session = $_SESSION['username'];

echo '
<link rel="stylesheet" href="css/items.css">

<div class="user-bar">
    <div class="user-dropdown">
        <button class="user-button">' . htmlspecialchars($username_session) . ' ▼</button>
        <div class="user-dropdown-content">
            <a href="show_user.php?user={$username}">Mi Usuario</a>
            <a href="logout.php">Cerrar Sesión</a>
        </div>
    </div>
</div>

<div class="container">
  <div class="content">
    <h1>LISTA DE ITEMS</h1>
    <table border="1" cellpadding="10" cellspacing="0">
      <tr>
        <th>Nombre</th>
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
                <td>
                    <a href='show_item.php?item={$nombre}'><button>Ver</button></a>
                    <a href='modify_item.php?item={$nombre}'><button>Modificar</button></a>
                    <a href='delete_item.php?item={$nombre}'><button>Eliminar</button></a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No hay items en la base de datos</td></tr>";
}

$conn->close();

echo '
    </table>
  </div>
</div>
<script src="js/items.js" defer></script>
';
?>
