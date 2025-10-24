<?php
require 'connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    exit(json_encode(['status' => 'error', 'message' => 'Método no permitido']));
}

if (!isset($_POST['usuario']) || !isset($_POST['password'])) {
    http_response_code(400);
    exit(json_encode(['status' => 'error', 'message' => 'Faltan campos requeridos']));
}

$usuario = $_POST["usuario"];
$pass = $_POST["password"];

$res = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario=?");
$res->bind_param("s", $usuario);
$res->execute();
$user = $res->get_result()->fetch_assoc();

header('Content-Type: application/json');

if ($user) {
    $stored = $user['contrasena'];
    // accept hashed passwords (password_verify) or legacy plain-text for compatibility
    $ok = false;
    if (password_verify($pass, $stored)) {
        $ok = true;
    } elseif ($pass === $stored) { // legacy support
        $ok = true;
    }

    if ($ok) {
        $_SESSION["user"] = $user["id"];
        echo json_encode([
            'status' => 'ok',
            'redirect' => 'home.php'
        ]);
        exit;
    }
}

echo json_encode([
    'status' => 'error',
    'message' => 'Usuario o contraseña incorrectos'
]);
?>