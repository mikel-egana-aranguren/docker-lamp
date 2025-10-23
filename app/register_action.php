<?php
require 'connect.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo "Method not allowed";
    exit;
}

$nombre = $_POST["nombre"] ?? '';
$apellidos = $_POST["apellidos"] ?? '';
$dni = strtoupper($_POST["dni"] ?? '');
$telefono = $_POST["telefono"] ?? '';
$fecha = $_POST["fecha"] ?? '';
$email = $_POST["email"] ?? '';
$usuario = $_POST["usuario"] ?? '';
$raw_pass = $_POST["password"] ?? '';

// Server-side validations
$errors = [];
if (!preg_match("/^[\p{L} '´`\.\-]{2,100}$/u", $nombre)) {
    $errors[] = 'Nombre no válido.';
}
if (!preg_match("/^[\p{L} '´`\.\-]{2,100}$/u", $apellidos)) {
    $errors[] = 'Apellidos no válidos.';
}
if (!preg_match('/^\d{8}-[A-Z]$/', $dni)) {
    $errors[] = 'DNI formato incorrecto.';
} else {
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $num = intval(substr($dni,0,8));
    $ltr = substr($dni,9,1);
    if ($letras[$num % 23] !== $ltr) {
        $errors[] = 'Letra del DNI no corresponde.';
    }
}
if (!preg_match('/^\d{9}$/', $telefono)) {
    $errors[] = 'Teléfono debe tener 9 dígitos.';
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email no válido.';
}
if (!preg_match('/^[A-Za-z0-9_\-]{3,50}$/', $usuario)) {
    $errors[] = 'Nombre de usuario no válido.';
}
if (strlen($raw_pass) < 6) {
    $errors[] = 'La contraseña es demasiado corta (mín 6).';
}

if (count($errors) > 0) {
    // Return errors as JSON to be handled by the client
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

$pass = password_hash($raw_pass, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO usuarios 
    (nombre, apellidos, dni, telefono, fecha_nacimiento, email, nombre_usuario, contrasena) 
    VALUES (?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssss", $nombre, $apellidos, $dni, $telefono, $fecha, $email, $usuario, $pass);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'Registro completado']);
} else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'errors' => ["DB error: " . $stmt->error]]);
}

?>
