<?php
require 'connect.php';

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST["usuario"];
    $pass = $_POST["password"];

    $res = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario=?");
    $res->bind_param("s", $usuario);
    $res->execute();
    $user = $res->get_result()->fetch_assoc();

    if ($user["nombre_usuario"] == $usuario && $pass == $user["contrasena"]) {
        $_SESSION["user"] = $user["id"];
        header("Location: home.php");
        exit;
    } else {
        echo "<p>Usuario o contraseña incorrectos.</p>";
    }
}
?>
<form id="login_form" method="POST">
  <input name="usuario" placeholder="Usuario" required><br>
  <input type="password" name="password" placeholder="Contraseña" required><br>
  <button id="login_submit" type="submit">Entrar</button>
</form>

