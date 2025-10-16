<?php
session_start();//iniciar sesion, es una funcion nativa de PHP
require_once __DIR__ . '/connectdb.php'; //Conectarse a la BD
$request = $_SERVER['REQUEST_URI']; //Obtener la ruta
switch ($request) { //Rutas
    case '/':
        //Página inicio
        require __DIR__ . '/home.php';
        break;

    case '/register':
        //Registro
        require __DIR__ . '/register.php';
        break;

    case '/login':
        //iniciar sesión
        require __DIR__ . '/login.php';
        break;

    case '/items':
        //Listado de items
        require __DIR__ . '/items.php';
        break;

    default:
        // Página no encontrada
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}

?>
