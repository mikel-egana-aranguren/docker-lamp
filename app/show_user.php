<?php
// phpinfo();
$hostname = "db";
$username = "admin";
$password = "test";
$dbname   = "database";

// Conexión a la base de datos
$cn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$cn) {
  die("Error de conexión: " . mysqli_connect_error());
}

// Obtener clave del usuario (por id o email)
$userKey = isset($_GET['user']) ? trim($_GET['user']) : '';

$user = null;
if ($userKey !== '') {
  if (ctype_digit($userKey)) {
    // Buscar por ID
    $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE id = ?";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userKey);
  } else {
    // Buscar por email
    $sql = "SELECT id, name, apels, dni, email, tlf, fechaNcto FROM users WHERE email = ?";
    $stmt = mysqli_prepare($cn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $userKey);
  }
  mysqli_stmt_execute($stmt);
  $res  = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_assoc($res);
}

if (!$user) {
  echo "Usuario no encontrado.";
  exit;
}

// Mostrar la información del usuario
echo '
<link rel="stylesheet" href="css/show_user.css">
<div class="container">
  <div class="content">
    <h1>DATOS DEL USUARIO</h1>
    <div class="rellenar">

      <p><strong>ID:</strong> '.htmlspecialchars($user["id"]).'</p>
      <p><strong>Nombre:</strong> '.htmlspecialchars($user["name"]).'</p>
      <p><strong>Apellidos:</strong> '.htmlspecialchars($user["apels"]).'</p>
      <p><strong>DNI:</strong> '.htmlspecialchars($user["dni"]).'</p>
      <p><strong>Correo:</strong> '.htmlspecialchars($user["email"]).'</p>
      <p><strong>Teléfono:</strong> '.htmlspecialchars($user["tlf"]).'</p>
      <p><strong>Fecha de nacimiento:</strong> '.htmlspecialchars($user["fechaNcto"]).'</p>

    </div>

    <div class="botones">
      <a href="modify_user.php?user='.urlencode($userKey).'" class="boton">Modificar</a>
      <a href="list_users.php" class="boton-sec">Volver</a>
    </div>
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
    gap: 40px;
    font-family: Arial, sans-serif;
  }

  h1 {
    font-size: 48px;
    margin: 0;
  }

  .rellenar {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
    font-size: 22px;
  }

  .rellenar p {
    margin: 0;
  }

  .botones {
    display: flex;
    gap: 30px;
  }

  .boton, .boton-sec {
    display: inline-block;
    font-weight: bold;
    border-radius: 100px;
    text-decoration: none;
    border: 4px solid #000;
    font-size: 22px;
    box-shadow: 0 10px 10px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    padding: 20px 50px;
  }

  .boton {
    background-color: #000;
    color: #fff;
  }

  .boton:hover {
    background-color: #232323;
    border-color: #232323;
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0,0,0,0.2);
  }

  .boton-sec {
    background-color: #fff;
    color: #000;
  }

  .boton-sec:hover {
    background-color: #eee;
    transform: translateY(-5px);
  }
</style>
';
?>



