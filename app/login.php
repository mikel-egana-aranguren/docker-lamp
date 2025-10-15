<?php
echo '
<link rel="stylesheet" href="css/login.css">
<div class="container">
  <div class="content">
    <h1>INICIAR SESIÓN</h1>
    <div class="rellenar">
    <form id="login_form" action="login.php" method="post" class="labels">
      <label for="email">Correo</label>
      <input type="text" id="email" name="email" required>

      <label for="passwd">Contraseña</label>
      <input type="password" id="passwd" name="passwd" required>
    </div>
      <button type="submit" id="login_submit">Confirmar</button>
    </form>
  </div>
</div>

<script src="js/login.js" defer></script>';
// phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

// datos introducidos
  $email = $_POST['email'];       
  $passwd = $_POST['passwd'];
  
$conn = mysqli_connect($hostname,$username,$password,$db);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

mysqli_set_charset($conn, 'utf8mb4');
$email_esc = mysqli_real_escape_string($conn, $email);

$sql="SELECT contrasena FROM usuario WHERE correo= '$email_esc'";

$result = mysqli_query($conn, $sql);
if (!$result) {
    error_log("DB query error: " . mysqli_error($conn));
    echo "Error interno.";
    mysqli_close($conn);
    exit;
}

if (mysqli_num_rows($result) === 0) {
    echo '<script>alert("Usuario o contraseña incorrectos.");</script>';
    mysqli_free_result($result);
    mysqli_close($conn);
    exit;
}
?>

