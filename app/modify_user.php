<?php
// modify_user.php (versión final solicitada)
// Ajusta credenciales si son distintas
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

// Conexión
$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Helper: preparar o devolver error legible
function prepare_or_die($cn, $sql, $context = '') {
    $stmt = mysqli_prepare($cn, $sql);
    if (!$stmt) {
        die("Error en prepare() {$context}: " . mysqli_error($cn) . " — SQL: {$sql}");
    }
    return $stmt;
}

// Obtener clave de búsqueda (por GET)
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;
$errorMsg = "";
$successMsg = "";
$warnings = [];

// Si viene user, intentamos cargar al usuario (correo / teléfono / dni / nombre)
if ($userKey !== '') {
    if (filter_var($userKey, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE correo = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $sql, 'SELECT por correo');
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    } elseif (preg_match('/^\+?\d[\d\s\-\(\)]{6,}\d$/', $userKey) && preg_replace('/\D+/', '', $userKey) !== '') {
        $tel_norm = preg_replace('/\D+/', '', $userKey);
        $sql = "SELECT dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento FROM `usuario` WHERE telefono = ? LIMIT 1";
        $stmt = prepare_or_die($cn, $

