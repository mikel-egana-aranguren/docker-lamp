<?php
session_start();
// phpinfo();
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$creditos = $_POST['creditos'];
$convocatorias_usadas = $_POST['convocatorias_usadas'];
$dni = $_SESSION['dniUsuario'];

if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
    $imagenTmp = $_FILES['imagen']['tmp_name'];
    $datosBinariosImagen = file_get_contents($imagenTmp);
} else {
    $datosBinariosImagen = null; // Si no se selecciona una imagen
}
$datosBinariosImagen = mysqli_real_escape_string($conn, $datosBinariosImagen);

$query = "INSERT INTO asignaturas (nombre, descripcion, creditos, convocatorias_usadas , imagen, dni) VALUES ('$nombre', '$descripcion', '$creditos', '$convocatorias_usadas', '$datosBinariosImagen', '$dni')";
$result = mysqli_query($conn, $query);

if ($result) {

    header('Location: dashboard.php');
} else {

    echo "Error al añadir: " . $conn->error;
    
}

mysqli_close($conn);
?>

