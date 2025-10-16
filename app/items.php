<?php
echo '
<link rel="stylesheet" href="css/items.css">
<div class="buttons">
  <a href="modify_item.php" class="btn-login">Modificar Coches</a>
  <a href="delete_item.php" class="btn-login">Eliminar Coches</a>
</div>
<div class="container">
  <div class="content">
    <h1>LISTA DE ITEMS</h1>
    <table border="1" cellpadding="10" cellspacing="0">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
';

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT id, nombre, descripcion FROM items";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = $row['nombre'];
        echo "<tr>
                <td>{$row['nombre']}</td>
                <td>{$row['descripcion']}</td>
                <td>
                    <a href='show_item.php?id=$id'><button>Ver</button></a>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='4'>No hay items en la base de datos</td></tr>";
}

$conn->close();

echo '
<link rel="stylesheet" href="css/login.css">
    </table>
  </div>
</div>

<script>
function confirmDelete(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este ítem?")) {
    window.location.href = "delete_item.php?id=" + id;
  }
}
</script>
';
?>
