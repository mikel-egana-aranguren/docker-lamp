<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

session_start();
// Generación de token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Conexión con la base de datos
$conexion = mysqli_connect($hostname, $username, $password, $db);
if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Comprobación de token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Error de seguridad: token CSRF inválido.");
    }
    // Escapar y limpiar los datos por seguridad
    $nombre = mysqli_real_escape_string($conexion, $_POST['item_name']);
    $anio = intval($_POST['item_year']);
    $combustible = mysqli_real_escape_string($conexion, $_POST['item_combustible']);
    $caballos = floatval($_POST['item_caballos']);
    $precio = floatval($_POST['precio']);

    // Preparar e insertar
    $stmt = $conexion->prepare("INSERT INTO item (nombre, anio, combustible, caballos, precio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisid", $nombre, $anio, $combustible, $caballos, $precio);
    
    // Mensaje que aparece por pantalla dependiendo del estado del proceso
    if ($stmt->execute()) {
        $message = "<p style='color:green;'>Coche añadido correctamente.</p>";
    } else {
        $error_code = mysqli_errno($conexion);
        $error_msg = mysqli_error($conexion);

        if ($error_code == 1062) {
            $message = "<p style='color:red;'>Este coche ya existe.</p>";
        } else {
            $message = "<p style='color:red;'>Error al añadir el coche: $error_msg</p>";
        }
    }
    $stmt->close();
}

// Cerramos conexión con la base de datos
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Añadir Coche</title>
    <link rel="stylesheet" href="css/add_item.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="js/add_item.js" defer></script>
</head>
<body>
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
                <input type="number" id="item_caballos" name="item_caballos" min="1" max="2000" required>

                <label for="precio">Precio (Máx 12 cifras)</label>
                <input type="number" id="precio" name="precio" min="1" max="999999999999" required>
                
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                <button type="submit" id="item_add_submit">Añadir</button>
            </form>
        </div>
    </div>
</body>
</html>
