<?php
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$cn = mysqli_connect($hostname, $username, $password, $db);
if (!$cn) {
    die("Error de conexión: " . mysqli_connect_error());
}

$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;
if ($userKey !== '') {
    if (ctype_digit($userKey)) {
        $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE id = ?";
        $stmt = mysqli_prepare($cn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userKey);
    } else {
        $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE email = ?";
        $stmt = mysqli_prepare($cn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $userKey);
    }
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
}

$successMsg = "";
$errorMsg = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id         = $_POST['id'] ?? null;
    $name       = trim($_POST['name'] ?? '');
    $apels      = trim($_POST['Apels'] ?? '');
    $dni        = trim($_POST['dni'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $tlf        = trim($_POST['tlf'] ?? '');
    $fechaNcto  = trim($_POST['fechaNcto'] ?? '');
    $passwd     = $_POST['passwd'] ?? '';
    $passwd_r   = $_POST['passwd_repeat'] ?? '';

    // Validaciones
    $nameOk  = (bool) preg_match('/^[A-Za-zÀ-ÿ]+$/', $name);
    $apelsOk = (bool) preg_match('/^[A-Za-zÀ-ÿ ]+$/', $apels);
    $emailOk = (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    $tlfOk   = (bool) preg_match('/^\d{9}$/', $tlf);
    $fechaOk = (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNcto);

    $dniOk = false;
    if (preg_match('/^(\d{8})-([A-Za-z])$/', $dni, $m)) {
        $num   = (int)$m[1];
        $letra = strtoupper($m[2]);
        $tabla = "TRWAGMYFPDXBNJZSQVHLCKE";
        $dniOk = ($tabla[$num % 23] === $letra);
    }

    if (!$nameOk || !$apelsOk || !$emailOk || !$tlfOk || !$fechaOk || !$dniOk) {
        $errorMsg = "Algún campo no cumple el formato.";
    } else {
        if ($passwd !== '') {
            if ($passwd !== $passwd_r) {
                $errorMsg = "Las contraseñas no coinciden.";
            } else {
                $hash = password_hash($passwd, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET name=?, apels=?, dni=?, email=?, tlf=?, fechaNcto=?, passwd=? WHERE id=?";
                $stmt = mysqli_prepare($cn, $sql);
                mysqli_stmt_bind_param($stmt, "sssssssi", $name, $apels, $dni, $email, $tlf, $fechaNcto, $hash, $id);
                mysqli_stmt_execute($stmt);
                $successMsg = "Datos actualizados.";
            }
        } else {
            $sql = "UPDATE users SET name=?, apels=?, dni=?, email=?, tlf=?, fechaNcto=? WHERE id=?";
            $stmt = mysqli_prepare($cn, $sql);
            mysqli_stmt_bind_param($stmt, "ssssssi", $name, $apels, $dni, $email, $tlf, $fechaNcto, $id);
            mysqli_stmt_execute($stmt);
            $successMsg = "Datos actualizados.";
        }

        // Recargar datos actualizados
        $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE id = ?";
        $stmt = mysqli_prepare($cn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        $res  = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($res);
    }
}
?>

<link rel="stylesheet" href="css/modify_user.css">

<div class="container">
  <div class="content">
    <h1>MODIFICAR USUARIO</h1>
    <?php if ($errorMsg): ?>
      <div class="error"><?= htmlspecialchars($errorMsg) ?></div>
    <?php endif; ?>
    <?php if ($successMsg): ?>
      <div class="success"><?= htmlspecialchars($successMsg) ?></div>
    <?php endif; ?>
    <div class="rellenar">
      <form id="user_modify_form" action="modify_user.php?user=<?= htmlspecialchars($userKey) ?>" method="post" class="labels">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <label for="name">Nombre *</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>

        <label for="Apels">Apellidos *</label>
        <input type="text" id="Apels" name="Apels" value="<?= htmlspecialchars($user['apels']) ?>" required>

        <label for="dni">DNI *</label>
        <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($user['dni']) ?>" required>

        <label for="email">Correo *</label>
        <input type="text" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

        <label for="tlf">Teléfono *</label>
        <input type="text" id="tlf" name="tlf" value="<?= htmlspecialchars($user['tlf']) ?>" required>

        <label for="fechaNcto">Fecha de Nacimiento *</label>
        <input type="date" id="fechaNcto" name="fechaNcto" value="<?= htmlspecialchars($user['fechaNcto']) ?>" required>

        <details>
          <summary>Cambiar contraseña (opcional)</summary>
          <label for="passwd">Contraseña</label>
          <input type="password" id="passwd" name="passwd">

          <label for="passwd_repeat">Repetir Contraseña</label>
          <input type="password" id="passwd_repeat" name="passwd_repeat">
        </details>

        <button type="submit" id="user_modify_submit">Guardar cambios</button>
      </form>
    </div>
  </div>
</div>

