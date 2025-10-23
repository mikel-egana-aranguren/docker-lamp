<?php

session_start();
require_once __DIR__ . '/connect.php';

// Obtener la ruta sin parámetros
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// --- DEBUG OPCIONAL ---
// echo "<pre>Ruta solicitada: $request</pre>";

switch ($request) {
    // Home
    case '/':
    case '/index.php':
        require __DIR__ . '/home.php';
        break;

    // Registro
    case '/register':
        require __DIR__ . '/register.php';
        break;

    // Login
    case '/login':
        require __DIR__ . '/login.php';
        break;

    // Modificación de usuario
    case '/modify_user':
        require __DIR__ . '/modify_user.php';
        break;

    // Listado de items
    case '/items':
        require __DIR__ . '/items.php';
        break;

    // Añadir item
    case '/add_item':
        require __DIR__ . '/add_item.php';
        break;

    // Ver un item concreto
    case '/show_item':
        require __DIR__ . '/show_item.php';
        break;
    // Modificar item
    case '/modify_item':
        require __DIR__ . '/modify_item.php';
        break;

    // Eliminar item
    case '/delete_item':
        require __DIR__ . '/delete_item.php';
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}
?>

