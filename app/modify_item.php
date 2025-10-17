<?php
// Activar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

// --- PARÁMETROS Y CONEXIÓN A LA BD ---
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = new mysqli($hostname, $username, $password, $db);
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// --- INICIALIZACIÓN DE VARIABLES ---
$mensaje = "";
$errors = []; 
$pelicula = null; // Inicializar la variable es una buena práctica

// --- OBTENER ID DE LA PELÍCULA ---
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pelicula = intval($_GET['id']);
} else {
    die("Error: No se ha especificado un ID de película válido.");
}

// --- PROCESAR EL FORMULARIO (CUANDO SE ENVÍA) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_submit'])) {
    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $duracion = $_POST['duracion'];
    
    // VALIDAR SOLO SI EL CAMPO NO ESTÁ VACÍO
    if (!empty($titulo) && strlen($titulo) < 2) $errors[] = "El título debe tener al menos 2 caracteres.";
    if (!empty($anio) && (!preg_match('/^\d{4}$/', $anio) || (int)$anio < 1900 || (int)$anio > (int)date('Y'))) $errors[] = "Año inválido.";
    if (!empty($director) && strlen($director) < 2) $errors[] = "El director debe tener al menos 2 caracteres.";
    if (!empty($genero) && strlen($genero) < 2) $errors[] = "El género debe tener al menos 2 caracteres.";
    if (!empty($duracion) && (!ctype_digit($duracion) || (int)$duracion <= 0)) $errors[] = "La duración debe ser un número entero positivo.";

    // SI NO HAY ERRORES, PROCEDER CON LA ACTUALIZACIÓN
    if (empty($errors)) {
        $campos_a_actualizar = [];
        $tipos_de_datos = '';
        $valores = [];
        
        if (!empty($titulo)) { $campos_a_actualizar[] = "titulo = ?"; $tipos_de_datos .= 's'; $valores[] = $titulo; }
        if (!empty($anio)) { $campos_a_actualizar[] = "anio = ?"; $tipos_de_datos .= 'i'; $valores[] = $anio; }
        if (!empty($director)) { $campos_a_actualizar[] = "director = ?"; $tipos_de_datos .= 's'; $valores[] = $director; }
        if (!empty($genero)) { $campos_a_actualizar[] = "genero = ?"; $tipos_de_datos .= 's'; $valores[] = $genero; }
        if (!empty($duracion)) { $campos_a_actualizar[] = "duracion = ?"; $tipos_de_datos .= 'i'; $valores[] = $duracion; }
    
        if (!empty($campos_a_actualizar)) {
            $sql_update = "UPDATE pelicula SET " . implode(', ', $campos_a_actualizar) . " WHERE idPelicula = ?";
            $tipos_de_datos .= 'i';
            $valores[] = $id_pelicula;
            
            $stmt = $conn->prepare($sql_update);
            if ($stmt) {
                $stmt->bind_param($tipos_de_datos, ...$valores);
                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    header("Location: items.php");
                    exit();
                } else {
                    $mensaje = "<p style='color:red;'>Error al guardar: " . htmlspecialchars($stmt->error) . "</p>";
                }
                $stmt->close(); // Cerrar también si la ejecución falla
            } else {
                 $mensaje = "<p style='color:red;'>Error al preparar la consulta: " . htmlspecialchars($conn->error) . "</p>";
            }
        } else {
            $mensaje = "<p style='color:orange;'>No se introdujo ningún dato para modificar.</p>";
        }
    } 
} 

// --- OBTENER DATOS ACTUALES PARA MOSTRAR EN EL FORMULARIO ---
$stmt_select = $conn->prepare("SELECT * FROM pelicula WHERE idPelicula = ?");
$stmt_select->bind_param("i", $id_pelicula);
$stmt_select->execute();
$result = $stmt_select->get_result();
if ($result->num_rows > 0) {
    $pelicula = $result->fetch_assoc();
} else {
    // Si la película no existe, no podemos continuar.
    $conn->close();
    die("Error: La película con el ID proporcionado no existe.");
}
$stmt_select->close();
$conn->close(); 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modificar Película</title>
    <link rel="stylesheet" href="inicioStyle.css">
</head>
<body style="text-align: center;">

    <?php if ($pelicula): // Comprobar que la película existe antes de mostrar el HTML ?>
    <h1>Modificar: <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
    <p style="color: #555;">Rellena solo los campos que quieras cambiar.</p>

    <?php if (!empty($errors)): ?>
        <div style="color: red; border: 1px solid red; padding: 10px; margin-bottom: 20px; text-align: left; display: inline-block;">
            <strong>Por favor, corrige los siguientes errores:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($mensaje)): ?>
        <div style="margin-bottom: 20px;"><?php echo $mensaje; ?></div>
    <?php endif; ?>

    <form name="modify_form" method="post" action="modify_item.php?id=<?php echo $id_pelicula; ?>">
        Título:<br>
        <input type="text" name="titulo" id="titulo" placeholder="Actual: <?php echo htmlspecialchars($pelicula['titulo']); ?>">
        
        <br>Año:<br>
        <input type="number" name="anio" id="anio" placeholder="Actual: <?php echo htmlspecialchars($pelicula['año']); ?>">
        
        <br>Director:<br>
        <input type="text" name="director" id="director" placeholder="Actual: <?php echo htmlspecialchars($pelicula['director']); ?>">
        
        <br>Género:<br>
        <input type="text" name="genero" id="genero" placeholder="Actual: <?php echo htmlspecialchars($pelicula['genero']); ?>">
        
        <br>Duración (minutos):<br>
        <input type="number" name="duracion" id="duracion" placeholder="Actual: <?php echo htmlspecialchars($pelicula['duracion']); ?>">

        <br><br>
        
        <input type="submit" value="Guardar Cambios" name="modify_submit" class="button save-button">
    </form>
        
    <div class="button-container">
        <a href="delete_item.php?id=<?php echo $pelicula['idPelicula']; ?>" class="button delete-button">Eliminar</a>
        <a href="items.php" class="button back-button">Volver</a>
    </div>
    <?php endif; ?>

</body>
</html>