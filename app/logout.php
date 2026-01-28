<?php
session_start();

// Sólo aceptar POST para cerrar sesión
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: index.php');
  exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        http_response_code(403);
        die('Error: CSRF token inválido.');
    }}

// Vaciar datos de sesión
$_SESSION = [];

// Borrar cookie de sesión si existe
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(session_name(), '', time() - 42000,
    $params["path"], $params["domain"],
    $params["secure"], $params["httponly"]
  );
}

// Destruir sesión en servidor
session_destroy();

// Redirigir a index.php
header('Location: index.php');
exit;
?>