<?php

$cn = mysqli_connect($hostname, $username, $password, $dbname);
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
  $res  = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($res);
}


$successMsg = "";
$errorMsg   = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id         = $_POST['id'] ?? null;
  $name       = trim($_POST['name'] ?? '');
  $apels      = trim($_POST['Apels'] ?? '');         // ojo: en tu form el name es "Apels"
  $dni        = trim($_POST['dni'] ?? '');
  $email      = trim($_POST['email'] ?? '');
  $tlf        = trim($_POST['tlf'] ?? '');
  $fechaNcto  = trim($_POST['fechaNcto'] ?? '');
  $passwd     = $_POST['passwd'] ?? '';
  $passwd_r   = $_POST['passwd_repeat'] ?? '';


  $nameOk   = (bool) preg_match('/^[A-Za-zÀ-ÿ]+$/', $name);
  $apelsOk  = (bool) preg_match('/^[A-Za-zÀ-ÿ ]+$/', $apels);
  $emailOk  = (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
  $tlfOk    = (bool) preg_match('/^\d{9}$/', $tlf);
  $fechaOk  = (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $fechaNcto);

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
    // Actualizar (si viene contraseña y coincide, se actualiza también)
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

    $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE id = ?";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $res  = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($res);
  }
}


if (!$user) {
  echo "Usuario no encontrado.";
  exit;
}
?>





echo '
<div class="container">
  <div class="content">
    <h1>MODIFICAR USUARIO</h1>
   <div class="rellenar">
    <form id="user_modify_form" action="modify_user.php?user='.htmlspecialchars($userKey).'" method="post" class="labels">

      <input type="hidden" name="id" value="'.htmlspecialchars($user["id"]).'">

      <label for="name">Nombre *</label>
      <input type="text" id="name" name="name" value="'.htmlspecialchars($user["name"]).'" required>

      <label for="Apels">Apellidos *</label>
      <input type="text" id="Apels" name="Apels" value="'.htmlspecialchars($user["Apels"]).'" required>

      <label for="dni">DNI *</label>
      <input type="text" id="dni" name="dni" value="'.htmlspecialchars($user["dni"]).'" required>

      <label for="email">Correo *</label>
      <input type="text" id="email" name="email" value="'.htmlspecialchars($user["email"]).'" required>

      <label for="tlf">Teléfono *</label>
      <input type="text" id="tlf" name="tlf" value="'.htmlspecialchars($user["tlf"]).'" required>

      <label for="fechaNcto">Fecha de Nacimiento *</label>
      <input type="date" id="fechaNcto" name="fechaNcto" value="'.htmlspecialchars($user["fechaNcto"]).'" required>

      <details>
        <summary>Cambiar contraseña (opcional)</summary>
        <label for="passwd">Contraseña</label>
        <input type="password" id="passwd" name="passwd">

        <label for="passwd_repeat">Repetir Contraseña</label>
        <input type="password" id="passwd_repeat" name="passwd_repeat">
      </details>
    </div>
      <button type="submit" id="user_modify_submit">Guardar cambios</button>
    </form>
  </div>
</div>



<style>
  html, body { 
  	margin:0; 
	padding:0; 
	height:100%; 
	overflow: hidden;
 }

  .container {
    display: grid;
    place-items: center;
    height: 100vh;
    box-sizing: border-box;
  }

  .content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 50px;
    font-family: Arial, sans-serif;
  }

  h1 {
    font-size: 48px;
    margin: 0;
  }

  .labels {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 20px;
  }

  label {
    font-weight: bold;
    font-size: 22px;
  }

  input {
    padding: 10px 20px;
    font-size: 18px;
    border-radius: 20px;
    border: 2px solid #000;
    outline: none;
  }

  button {
    display: inline-block;
    font-weight: bold;
    border-radius: 100px;
    text-decoration: none;
    border: 4px solid #000;
    font-size: 22px;
    box-shadow: 0 10px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    background-color: #000;
    color: #fff;
    padding: 20px 50px;
  }
  button:hover {
    background-color: #232323;
    color: #fff;
    border-color: #232323;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
  }
</style>

<script>

const form = document.getElementById("user_modify_form");
const nameInput = document.getElementById("name");
const apelsInput = document.getElementById("Apels");
const dniInput = document.getElementById("dni");
const emailInput = document.getElementById("email");
const tlfInput = document.getElementById("tlf");
const fechaNctoInput = document.getElementById("fechaNcto");
const passInput = document.getElementById("passwd");
const passRepeatInput = document.getElementById("passwd_repeat");

function letraDNI(num) {
  const letras = "TRWAGMYFPDXBNJZSQVHLCKE";
  return letras[num % 23];
}

form.addEventListener("submit", function(event) {
  const name = nameInput.value.trim();
  const apels = apelsInput.value.trim();
  const dni = dniInput.value.trim();
  const email = emailInput.value.trim();
  const tlf = tlfInput.value.trim();
  const fechaNcto = fechaNctoInput.value.trim();
  const p1 = passInput.value;
  const p2 = passRepeatInput.value;

  const nameRegex = /^[A-Za-zÀ-ÿ]+$/;
  const apelsRegex = /^[A-Za-zÀ-ÿ ]+$/;
  const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
  const tlfRegex = /^\\d{9}$/;
  const fechaRegex = /^\\d{4}-\\d{2}-\\d{2}$/;
  const dniRegex = /^(\\d{8})-([A-Za-z])$/;

  if (!nameRegex.test(name)) {
    alert("Por favor, introduce un nombre válido."); event.preventDefault(); return;
  }
  if (!apelsRegex.test(apels)) {
    alert("Por favor, introduce apellidos válidos."); event.preventDefault(); return;
  }
  if (!dniRegex.test(dni)) {
    alert("Por favor, introduce un DNI válido, debe ser 12345678-Z."); event.preventDefault(); return;
  } else {
    const m = dni.match(dniRegex);
    const numero = parseInt(m[1], 10);
    const letra = m[2].toUpperCase();
    if (letraDNI(numero) !== letra) {
      alert("La letra del DNI no corresponde al número.");
      event.preventDefault(); return;
    }
  }
    if (!emailRegex.test(email)) {
        alert("Por favor, introduce un correo válido (usuario@servidor.extension).");
        event.preventDefault();
    }
  if (!tlfRegex.test(tlf)) {
    alert("Por favor, introduce un teléfono válido, debe tener 9 dígitos."); event.preventDefault(); return;
  }
  if (!fechaRegex.test(fechaNcto)) {
    alert("Por favor, introduce una fecha válida, debe ser aaaa-mm-dd."); event.preventDefault(); return;
  }
  if (p1 !== "" || p2 !== "") {
    if (p1 !== p2) { alert("Las contraseñas no coinciden."); event.preventDefault(); return; }
    if (p1.length < 4) { alert("Contraseña demasiado corta (mín. 4)."); event.preventDefault(); return; }
  }
});
</script>
';
// phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";
?>



