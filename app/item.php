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
      </tr>
';

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

// Conectar a la base de datos
$conn = new mysqli($hostname, $username, $password, $db);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consultar todos los items
$sql = "SELECT id, nombre, descripcion FROM items";
$result = $conn->query($sql);

// Mostrar los resultados
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['nombre']}</td>
                <td>{$row['descripcion']}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='3'>No hay items en la base de datos</td></tr>";
}

$conn->close();

echo '
    </table>
  </div>
</div>

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

  .labels {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
  }

  label {
    font-weight: bold;
    font-size: 20px;
  }

  input {
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 20px;
    border: 2px solid #000;
    outline: none;
  }

  button {
    display: inline-block;
    font-weight: bold;
    border-radius: 100px;
    text-decoration: none;
    border: 4px solid #000;
    font-size: 22px;
    box-shadow: 0 10px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background-color: #000;
    color: #fff;
    padding: 20px 50px;
  }

  button:hover {
    background-color: #232323;
    color: #fff;
    border-color: #232323;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
  }
</style>


<script>
const form = document.getElementById("item_form");
const nombreInput = document.getElementById("nombre");
const descripcionInput = document.getElementById("descripcion");

form.addEventListener("submit", function(event) {
  const nombre = nombreInput.value.trim();
  const descripcion = descripcionInput.value.trim();

  if (nombre.length < 2) {
    alert("El nombre debe tener al menos 2 caracteres.");
    event.preventDefault();
  } else if (descripcion.length < 5) {
    alert("La descripción debe tener al menos 5 caracteres.");
    event.preventDefault();
  }
});
</script>
';
// phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";
  ?>

