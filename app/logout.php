<?php
// Obtiene la sesión
session_start();

$_SESSION = [];

// Cerrar sesión
session_destroy();

header("Location: login.php");
exit;
?>
