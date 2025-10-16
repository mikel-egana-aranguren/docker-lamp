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
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $name       = $_POST['name'] ?? '';
        $surnames   = $_POST['surnames'] ?? '';
        $dni        = $_POST['dni'] ?? '';
        $email      = $_POST['email'] ?? '';
        $tlfn       = $_POST['tlfn'] ?? '';
        $fNcto      = $_POST['fNcto'] ?? '';
        $passwd     = $_POST['passwd'] ?? '';
        $passwd_repeat = $_POST['passwd_repeat'] ?? '';

        // ✅ Comprobación de contraseñas iguales
        if ($passwd !== $passwd_repeat) {
            $message = "Las contraseñas no coinciden.";
        } else {
            $sql = "INSERT INTO usuario (dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $message = "Error al preparar la consulta: " . $conn->error;
            } else {
                $stmt->bind_param("sssssss", $dni, $name, $surnames, $email, $passwd, $tlfn, $fNcto);

                if ($stmt->execute()) {
                    $message = "Usuario registrado correctamente.";
                    $message_color = "green";
                    $_POST = [];
                } else {
                    $message = "Error al registrar el usuario: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<link rel="stylesheet" href="css/register.css">

<div class="container">
  <div class="content">
    <h1>REGISTRARSE</h1>

    <?php if ($message !== ""): ?>
        <p style="color: <?= $message_color ?>; font-weight: bold; margin-bottom: 15px;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <div class="rellenar">
      <form id="register_form" action="" method="post" class="labels">
        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">

        <label for="surnames">Apellidos *</label>
        <input type="text" id="surnames" name="surnames" required value="<?= htmlspecialchars($_POST['surnames'] ?? '') ?>">

        <label for="dni">DNI *</label>
        <input type="text" id="dni" name="dni" required value="<?= htmlspecialchars($_POST['dni'] ?? '') ?>">

        <label for="email">Correo *</label>
        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="tlfn">Teléfono *</label>
        <input type="text" id="tlfn" name="tlfn" required value="<?= htmlspecialchars($_POST['tlfn'] ?? '') ?>">

        <label for="fNcto">Fecha de Nacimiento *</label>
        <input type="date" id="fNcto" name="fNcto" required value="<?= htmlspecialchars($_POST['fNcto'] ?? '') ?>">

        <label for="passwd">Contraseña *</label>
        <input type="password" id="passwd" name="passwd" required>

        <label for="passwd_repeat">Repetir Contraseña *</label>
        <input type="password" id="passwd_repeat" name="passwd_repeat" required>

        <button type="submit" id="register_submit">Confirmar</button>
      </form>
    </div>
  </div>
</div>

<script src="js/register.js" defer></script>
