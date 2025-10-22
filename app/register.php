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
        $user       = trim($_POST['user'] ?? '');
        $name       = trim($_POST['name'] ?? '');
        $surnames   = trim($_POST['surnames'] ?? '');
        $dni        = strtoupper(trim($_POST['dni'] ?? ''));
        $email      = trim($_POST['email'] ?? '');
        $tlfn       = trim($_POST['tlfn'] ?? '');
        $fNcto      = trim($_POST['fNcto'] ?? '');
        $passwd     = $_POST['passwd'] ?? '';
        $passwd_repeat = $_POST['passwd_repeat'] ?? '';

        // ⚠️ Validaciones básicas
        if ($passwd !== $passwd_repeat) {
            $message = "Las contraseñas no coinciden.";
        } elseif (
            $user === '' || $name === '' || $surnames === '' ||
            $dni === '' || $email === '' || $tlfn === '' || $fNcto === '' || $passwd === ''
        ) {
            $message = "Por favor, completa todos los campos obligatorios.";
        } else {
            $sql = "INSERT INTO usuario 
                    (user, dni, nombre, apellidos, correo, contrasena, telefono, fecha_nacimiento)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                $message = "Error al preparar la consulta: " . $conn->error;
            } else {
                $stmt->bind_param(
                    "ssssssss",
                    $user, $dni, $name, $surnames, $email, $passwd, $tlfn, $fNcto
                );

                if ($stmt->execute()) {
                    $message = "✅ Usuario registrado correctamente.";
                    $message_color = "green";
                    $_POST = []; // limpiar el formulario
                } else {
                    if ($stmt->errno === 1062) { // Código de error MySQL para "Duplicate entry"
			    $errorText = $stmt->error;

			    if (strpos($errorText, "dni") !== false) {
				$message = "⚠️ El DNI ya está registrado.";
			    } elseif (strpos($errorText, "correo") !== false) {
				$message = "⚠️ El correo electrónico ya está registrado.";
			    } elseif (strpos($errorText, "telefono") !== false) {
				$message = "⚠️ El teléfono ya está registrado.";
			    } else {
				$message = "⚠️ El nombre de usuario ya está registrado.";
			    }
			} else {
			    $message = "Error al registrar el usuario: " . $stmt->error;
			}

                }

                $stmt->close();
            }
        }
    }
}

$conn->close();
?>

<link rel="stylesheet" href="css/register.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<div class="bar">
  <div class="volver_button">
    <a href="index.php" title="Volver al inicio">
      <i class="fa-solid fa-house"></i>
    </a>
  </div>
  <h1>REGISTRARSE</h1>
</div>

<div class="container">
  <div class="content">
    <?php if ($message !== ""): ?>
        <p style="color: <?= $message_color ?>; font-weight: bold; margin-bottom: 15px;">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>
    <div class="rellenar">
      <form id="register_form" action="" method="post" class="labels">
        <label for="user">Usuario *</label>
        <input type="text" id="user" name="user" required value="<?= htmlspecialchars($_POST['user'] ?? '') ?>">

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

