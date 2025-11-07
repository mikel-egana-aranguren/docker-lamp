<?php
$path = '/; SameSite=Strict'; 
$lifetime = 0;
$domain = '';
$secure = false; // Falso para localhost
$httponly = true; // Evita acceso desde JavaScript

//Evitar que el ID de sesión se pase por URL
ini_set('session.use_strict_mode', 1);
ini_set('session.use_only_cookies', 1);

session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
session_start();

?>