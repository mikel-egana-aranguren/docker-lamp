<?php
require 'connect.php';
$id = $_SESSION["user"];
$res = $conn->query("SELECT * FROM usuarios WHERE id=$id");
$user = $res->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $telefono = $_POST["telefono"];
    $email = $_POST["email"];
    $stmt = $conn->prepare("UPDATE usuarios SET telefono=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $telefono, $email, $id);
    $stmt->execute();
    echo "<p>Datos actualizados ✅</p>";
}
?>
<form id="user_modify_form" method="POST">
  <input name="telefono" value="<?= $user['telefono'] ?>"><br>
  <input name="email" value="<?= $user['email'] ?>"><br>
  <button id="user_modify_submit">Actualizar</button>
</form>

