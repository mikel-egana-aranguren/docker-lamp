<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$cn = mysqli_connect($hostname, $username, $password, $db);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Escapar y limpiar los datos por seguridad
    $nombre = mysqli_real_escape_string($cn, $_POST['item_name']);
    $anio = intval($_POST['item_year']);
    $combustible = mysqli_real_escape_string($cn, $_POST['item_combustible']);
    $caballos = floatval($_POST['item_caballos']);
    $precio = floatval($_POST['precio']);

    // Preparar e insertar
    $stmt = mysqli_prepare($cn, "INSERT INTO item (nombre, año, combustible, caballos, precio) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sisid", $nombre, $anio, $combustible, $caballos, $precio);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<p style='color:green;'>Coche añadido correctamente.</p>";
    } else {
        $error_code = mysqli_errno($cn);
        $error_msg = mysqli_error($cn);

        if ($error_code == 1062) {
            $message = "<p style='color:red;'>Este coche ya existe.</p>";
        } else {
            $message = "<p style='color:red;'>Error al añadir el coche: $error_msg</p>";
        }
    }

    mysqli_stmt_close($stmt);
}

mysqli_close($cn);
?>

<title>Añadir Coche</title>
<link rel="stylesheet" href="css/add_item.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="js/add_item.js" defer></script>
<div class="bar">
  <div class="volver_button">
    <a href="items.php" title="Volver al inicio">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>
  <h1>AÑADIR COCHE</h1>
</div>
<div class="container">
  <div class="content">
    <?= $message ?>

    <form id="item_add_form" action="add_item.php" method="post" class="labels">
     <label for="item_name">Nombre del coche (Marca + Modelo)</label>
     <input type="text" id="item_name" name="item_name" required>

     <label for="item_year">Año (>=1886)</label>
     <input type="number" id="item_year" name="item_year" min="1886" max="9999" required>

     <label for="item_combustible">Combustible</label>
     <input type="text" id="item_combustible" name="item_combustible" required>

     <label for="item_caballos">Caballos (1-2000)</label>
     <input type="number" id="item_caballos" name="item_caballos" min="1" max="2000"required>

     <label for="precio">Precio (Máx 12 cifras)</label>
     <input type="number" id="precio" name="precio" min="1" max="999999999999" required>

     <button type="submit" id="item_add_submit">Añadir</button>
    </form>
  </div>
</div>

