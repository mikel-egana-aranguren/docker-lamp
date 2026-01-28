<?php
$path = '/; SameSite=Strict'; 
$lifetime = 0;
$domain = '';
$secure = false; // Falso para localhost
$httponly = true; // <-- Soluciona Alerta 8

session_set_cookie_params($lifetime, $path, $domain, $secure, $httponly);
session_start();

?>