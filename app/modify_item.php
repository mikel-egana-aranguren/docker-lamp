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

$mensaje = "";
$errors = []; 
$pelicula = null; 

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_pelicula = intval($_GET['id']);
} else {
    die("Error: No se ha especificado un ID de película válido.");
}

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
                $stmt->close(); 
            } else {
                 $mensaje = "<p style='color:red;'>Error al preparar la consulta: " . htmlspecialchars($conn->error) . "</p>";
            }
        } else {
            $mensaje = "<p style='color:orange;'>No se introdujo ningún dato para modificar.</p>";
        }
    } 
} 

$stmt_select = $conn->prepare("SELECT * FROM pelicula WHERE idPelicula = ?");
$stmt_select->bind_param("i", $id_pelicula);
$stmt_select->execute();
$result = $stmt_select->get_result();
    // Preparar los arrays para construir la consulta dinámica
    $campos_a_actualizar = [];
    $tipos_de_datos = '';
    $valores = [];
	$sql_update = "UPDATE pelicula SET "; // Inicializar la consulta

    if (strlen($titulo) < 2) $errors[] = "El título debe tener al menos 2 caracteres.";
    if (!preg_match('/^\d{4}$/', $anio) || (int)$anio < 1900 || (int)$anio > (int)date('Y')) $errors[] = "Año inválido.";
    if (strlen($director) < 2) $errors[] = "El director debe tener al menos 2 caracteres.";
    if (strlen($genero) < 2) $errors[] = "El género debe tener al menos 2 caracteres.";
    if (!ctype_digit($duracion) || (int)$duracion <= 30 || (int)$duracion >= 52000 ) $errors[] = "La duración debe ser un número entero positivo asequible.";

    //Convertir caracteres especiales de los errores en html
    if (!empty($errors)) {
        foreach ($errors as $err) 
        {
            echo "<p style='color:red;'>" . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . "</p>";
        }
        echo '<br><a href="items.php">Volver</a>';
        $conn->close();
        exit;
    }
    
    // Verificamos cada campo. Si el usuario ha escrito algo, lo añadimos a la consulta.
    // .= para concatenar
	if (!empty($titulo)) {
        $campos_a_actualizar[] = "titulo = '$titulo'";          
    }
    if (!empty($anio)) {
        $campos_a_actualizar[] = "anio = '$anio'";
    }
    if (!empty($director)) {
        $campos_a_actualizar[] = "director = '$director'";
    }
    if (!empty($genero)) {
        $campos_a_actualizar[] = "genero = '$genero'";
    }
    if (!empty($duracion)) {
        $campos_a_actualizar[] = "duracion = '$duracion'";
    }
    // Solo ejecutamos la consulta si hay algo que actualizar
    if (!empty($campos_a_actualizar)) {
        $sql_update .= implode(', ', $campos_a_actualizar); // Une los campos con comas
        $sql_update .= " WHERE idPelicula = $id_pelicula"; // Condición para modificar solo la película correcta. Poner ? en vez de $pelicula para mas seguro
        
        $tipos_de_datos .= 'i'; // Añadimos el tipo del ID
        $valores[] = $id_pelicula; // Añadimos el valor del ID

        //Ejecutar cambios
        if($conn ->query($sql_update))
        {
            $conn->close();
            header("Location: items.php");
            exit();
        }
        else
        {
            $mensaje = "<p style='color:red;'>Error al guardar: " . htmlspecialchars($conn->errpr) . "</p>";
        }

        /*//Modificacion mas segura para otra entrega

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

    <?php if ($pelicula): ?>
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
        <input type="text" name="titulo" id="titulo" placeholder= "<?php echo htmlspecialchars($pelicula['titulo']); ?>">
        
        <br>Año:<br>
        <input type="number" name="anio" id="anio" placeholder="<?php echo htmlspecialchars($pelicula['año']); ?>">
        
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