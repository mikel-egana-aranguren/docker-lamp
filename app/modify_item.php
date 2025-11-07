<?php
require_once 'session_config.php';

// parametros paraconexion a la bd
$servername = "db";
$username = "admin";
$password = "test";
$dbname = "database";

// conexion a la bd
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
$mensaje = "";
$errors = []; 
$pelicula = null;
// OBTENER EL ID_PELÍCULA SELECCIONADA 
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pelicula = intval($_GET['id']);
} else {
    die("Error: No se ha especificado un ID de película válido.");
}

//PROCESAR EL FORMULARIO
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['modify_submit'])) {
    // validar el token CSRF
	if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
	http_response_code(403);
	die('Error: CSRF token inválido.');
	}
    $titulo = $_POST['titulo'];
    $anio = $_POST['anio'];
    $director = $_POST['director'];
    $genero = $_POST['genero'];
    $duracion = $_POST['duracion'];
    
    // Preparar los arrays para construir la consulta dinámica
    $campos_a_actualizar = [];
    $tipos_de_datos = '';
    $valores = [];
	$sql_update = "UPDATE pelicula SET "; // Inicializar la consulta
    
    // Verificamos cada campo. Si el usuario ha escrito algo, lo añadimos a la consulta.
    // .= para concatenar
	if (!empty($titulo)) {
         $campos_a_actualizar[] = "titulo = ?"; 
         $tipos_de_datos .= 's';                  // 's' = string
         $valores[] = $titulo;               
    }
    if (!empty($anio)) {
        $campos_a_actualizar[] = "anio = ?";
        $tipos_de_datos .= 's';                 
        $valores[] = $anio;
    }
    if (!empty($director)) {
        $campos_a_actualizar[] = "director = ?";
        $tipos_de_datos .= 's';
        $valores[] = $director;
    }
    if (!empty($genero)) {
        $campos_a_actualizar[] = "genero = ?";
        $tipos_de_datos .= 's';
        $valores[] = $genero;
    }
    if (!empty($duracion)) {
        $campos_a_actualizar[] = "duracion = ?";
        $tipos_de_datos .= 'i';
        $valores[] = $duracion;
    }

    if(!empty($campos_a_actualizar))
    {
        $sql_update .= implode(', ', $campos_a_actualizar); //unir los campos con comas a la sentencia SQL
        $sql_update .= " WHERE idPelicula = ?"; //los campos se actualizan para la pelicula objetivo
        $tipos_de_datos .= 'i'; //Agregar tipo de datos del id (int)
        $valores[] = $id_pelicula; //Agregar el id de la peli

        //Ejecutar cambios
	    $stmt = $conn->prepare($sql_update);
        if ($stmt) {
            $stmt->bind_param($tipos_de_datos, ...$valores);
            // Si la ejecución es exitosa...
            if ($stmt->execute()) {
                // Cerramos la conexión y redirigimos al usuario.

                $conn->close();
                header("Location: items.php"); // Le decimos al navegador que vaya a items.php
                exit(); // Detenemos el script para asegurar que la redirección ocurra.   
            } 
            else {
                $mensaje = "<p class='error-msg';>Error al guardar: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        } 
        else {
            $mensaje = "<p class='error-msg';>Error al preparar la consulta: " . htmlspecialchars($conn->error) . "</p>";
        }
    } 
    else {
        $mensaje = "<p class='error-msg';>No se introdujo ningún dato para modificar.</p>";
    }
}
//CARGAR DATOS ACTUALES DE LA PELÍCULA 
// Se necesita para mostrar los placeholders en el formulario
$stmt = $conn->prepare("SELECT * FROM pelicula WHERE idPelicula = ?");
$stmt->bind_param("i", $id_pelicula);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $pelicula = $result->fetch_assoc();

} else {
    die("Error: La película con el ID proporcionado no existe.");
}
$stmt->close();
$conn->close();
?><!DOCTYPE html>
<html>
<head>
    <title>Modificar Película</title>
    <link rel="stylesheet" href="inicioStyle.css">
    <script src="js/validar_peli_modify.js"></script>
</head>
<body class="modify_item">

    <h1>Modificar: <?php echo htmlspecialchars($pelicula['titulo']); ?></h1>
    <h2> Rellena los campos que quieras cambiar</h2>
    
    <form id="modify_item_form"class="modify_item"name="modify_form" method="post" action="">
        
        Título:<br>
        <input type="text" name="titulo" id="titulo" placeholder="<?php echo htmlspecialchars($pelicula['titulo']); ?>">
        
        <br>Año:<br>
        <input type="number" name="anio" id="anio" placeholder="<?php echo htmlspecialchars($pelicula['anio']); ?>">
        
        <br>Director:<br>
        <input type="text" name="director" id="director" placeholder="<?php echo htmlspecialchars($pelicula['director']); ?>">
        
        <br>Género:<br>
        <input type="text" name="genero" id="genero" placeholder="<?php echo htmlspecialchars($pelicula['genero']); ?>">
        
        <br>Duración (minutos):<br>
        <input type="number" name="duracion" id="duracion" placeholder="<?php echo htmlspecialchars($pelicula['duracion']); ?>">
        	<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
        <br><br>
        
        <input type="submit" value="Guardar Cambios" name="modify_submit" class="save-button">   
    </form>
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
    <div >
    <a href="delete_item.php?id=<?php echo $pelicula['idPelicula']; ?>" class="delete-button">Eliminar Película
    </a>
    <?php endif; ?>
</div>
<div class="button-container">
    <a href="items.php" class="button back-button">Volver</a>
</div>

</body>
</html>