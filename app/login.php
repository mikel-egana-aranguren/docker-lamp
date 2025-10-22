<?php
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

$message = "";
$message_color = "red";

$conn = new mysqli($hostname, $username, $password, $dbname);
if ($conn->connect_error) {
    $message = "Error de conexión a la base de datos: " . $conn->connect_error;
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'] ?? '';
        $passwd = $_POST['passwd'] ?? '';

        // Preparar consulta para buscar usuario
        $stmt = $conn->prepare("SELECT contrasena, user FROM usuario WHERE correo = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            $message = "Correo no registrado.";
        } else {
            $stmt->bind_result($db_pass, $user);
            $stmt->fetch();
        if ($db_pass === $passwd) {
            session_start();
            $_SESSION['usuario'] = $user;

            $message = "Login correcto. Redirigiendo...";
            $message_color = "green";

            header("Location: items.php");
            exit;
        }
 
        else { 
            $message = "Contraseña incorrecta."; 
        }    
        $stmt->close();
        }
  }
}
$conn->close();
?>

<link rel="stylesheet" href="css/login.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<script src="js/index.js" defer></script>
<div class="bar">
  <div class="volver_button">
    <a href="index.php" title="Volver al inicio">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>
  <h1>INICIAR SESIÓN</h1>
</div>

<div class="container">
  <div class="content">

    <?php if ($message !== ""): ?>
        <p style="color: <?= $message_color ?>; font-weight:bold; margin-bottom:15px;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <div class="rellenar">
      <form id="login_form" action="" method="post" class="labels">
        <label for="email">Correo (usuario@servidor.extension)</label>
        <input type="text" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="passwd">Contraseña</label>
        <input type="password" id="passwd" name="passwd" required>

        <button type="submit" id="login_submit">Confirmar</button>
      </form>
    </div>
  </div>
</div>

<script src="js/login.js" defer></script>

