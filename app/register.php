<?php
echo '
<link rel="stylesheet" href="css/register.css">
<div class="container">
  <div class="content">
    <h1>REGISTRARSE</h1>
    <div class="rellenar">
    <form id="register_form" action="register.php" method="post" class="labels">
      <label for="name">Nombre *</label>
      <input type="text" id="name" name="name" required>

      <label for="surnames">Apellidos *</label>
      <input type="text" id="surnames" name="surnames" required>
      
      <label for="dni">DNI *</label>
      <input type="text" id="dni" name="dni" required>
      
      <label for="email">Correo *</label>
      <input type="text" id="email" name="email" required>
      
      <label for="tlfn">Teléfono *</label>
      <input type="text" id="tlfn" name="tlfn" required>
      
      <label for="fNcto">Fecha de Nacimiento *</label>
      <input type="date" id="fNcto" name="fNcto" required>
      
      <label for="passwd">Contraseña *</label>
      <input type="password" id="passwd" name="passwd" required>
      
      <label for="passwd_repeat">Repetir Contraseña *</label>
      <input type="password" id="passwd_repeat" name="passwd_repeat" required>
    </div>
      <button type="submit" id="register_submit">Confirmar</button>
    </form>
  </div>
</div>

<script src="js/register.js" defer></script>';

// phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";
?>

