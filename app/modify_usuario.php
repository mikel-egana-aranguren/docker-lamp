<?php
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
$apellidos = $_POST['apellidos'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$email = $_POST['email'];
$password = $_POST['password'];

$consulta_email = "SELECT COUNT(*) as count FROM usuarios WHERE email = '$email' AND dni != '$dni'";
$result_email = mysqli_query($conn, $consulta_email);

if ($result_email) {
    $row_email = mysqli_fetch_assoc($result_email);
    $count = $row_email['count'];

    if ($count > 0) {
        echo '<script type="text/javascript">window.alert("El correo electrónico ya está en uso por otro usuario."); window.location.href = "editar_usuario.php?error=email_in_use";</script>';
        exit;
    }
}



$query = "UPDATE usuarios SET nombre = '$nombre', apellidos = '$apellidos', telefono = '$telefono' , fechaNacimiento = '$fechaNacimiento' , email = '$email', password = '$password' WHERE  dni = '$dni'";
$result = mysqli_query($conn, $query);

if ($result) {
    header('Location: dashboard.php');
} else {
    header('Location: dashboard.php?error=modify_asignatura_failed');
}

$conn->close();
?>

