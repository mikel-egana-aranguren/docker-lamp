<?php
echo '
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
        $id = $row['id'];
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['descripcion']}</td>
                <td>
                    <a href='show_item.php?id=$id'><button>Ver</button></a>
                    <a href='modify_item.php?id=$id'><button>Modificar</button></a>
                    <button onclick=\"confirmDelete($id)\">Eliminar</button>
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

<script>
function confirmDelete(id) {
  if (confirm("¿Estás seguro de que deseas eliminar este ítem?")) {
    window.location.href = "delete_item.php?id=" + id;
  }
}
</script>

<style>
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;
  }

  .container {
    display: grid;
    place-items: center;
    height: 100vh;
    box-sizing: border-box;
  }

  .content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 30px;
    font-family: Arial, sans-serif;
  }

  h1 {
    font-size: 48px;
    margin: 0;
  }

  table {
    border-collapse: collapse;
    font-size: 18px;
  }

  th, td {
    padding: 10px 20px;
    text-align: center;
  }

  button {
    display: inline-block;
    font-weight: bold;
    border-radius: 20px;
    border: 2px solid #000;
    background-color: #000;
    color: #fff;
    padding: 8px 16px;
    cursor: pointer;
    transition: 0.3s;
  }

  button:hover {
    background-color: #232323;
    transform: translateY(-2px);
  }

  td button {
    margin: 0 5px;
  }
</style>
';
?>
