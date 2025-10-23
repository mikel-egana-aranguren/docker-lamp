<?php
session_start(); 
if (!isset($_SESSION["user"])) {
    echo "<h3>No has iniciado sesión. <a href='/login.php'>Login</a></h3>";
    exit;
}
echo "<h2>Bienvenido</h2>";
echo "<a href='/items'>Ver elementos</a><br>";
echo "<a href='/modify_user?user={$_SESSION["user"]}'>Modificar mis datos</a>";
?>

