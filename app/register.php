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

$consulta_dni = "SELECT * FROM usuarios WHERE dni = '$dni'";
$result_dni = mysqli_query($conn, $consulta_dni);

$consulta_email = "SELECT * FROM usuarios WHERE email = '$email'";
$result_email = mysqli_query($conn, $consulta_email);


$consulta = true; 

if (mysqli_num_rows($result_dni) > 0) {
    echo "El DNI ya está en uso.";
    $consulta = false; // Establece $consulta como falso solo en caso de conflicto
    echo '<script type="text/javascript">window.alert("El dni ya está en uso por otro usuario."); window.location.href = "register.php";</script>';
} elseif (mysqli_num_rows($result_email) > 0) {
    $consulta = false; // Establece $consulta como falso solo en caso de conflicto
    echo '<script type="text/javascript">window.alert("El correo electrónico ya está en uso por otro usuario."); window.location.href = "register.php?error=email_in_use";</script';
    header('Location: register.html?error=email_in_use');
}

if ($consulta) {
    // Continúa con la inserción solo si no hay conflictos
    $sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fechaNacimiento, email, password) VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fechaNacimiento', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['mensaje_registro'] = "Registro exitoso. ¡Bienvenido!";
        echo '<script type="text/javascript">window.alert("Registro exitoso"); window.location.href = "inicio.html";</script>';
    } 
}

$conn->close();

