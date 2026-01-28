<?php
require_once 'session_config.php';

/**Algo de explicación: 
 * Estructura:
    - html: crea el formulario. Cuando se le da al botón, el .js está "escuchando" y 
    se encarga de validar los datos (formato etc.), tablas que hemos definido al crear la bd.
    - php: una vez validados los datos, se envían al php, que se encarga de comprobar si ya existe
    el usuario en la bd y si no existe, lo crea.
    + name del input (html) y el índice de $_POST (php) deben coincidir 
    + primero debe ir la parte de php y luego la de html porque la primera línea debe ser <?php sesion_start
    
index.php --> register.php :
               -php 
               -html (formulario) ---> js
*/
  // generar un token CSRF si no existe
  if (empty($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  // phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }

?>
<html>
   <head>
    <link rel="stylesheet" type="text/css" href="inicioStyle.css">
   </head>
   <body class="index">
      <h1>¡Bienvenid@ a SafeFilms!<h1>
    <?php
      if (isset($_SESSION['usuario'])) : ?>
        echo "<script>window.location.href='items.php';</script>";
        exit;

      <?php else: ?>
        <p>
        <h2>Consulta nuestro catálogo con lo mejor del cine: <h2>
        <button class="b1" onclick="window.location.href='items.php'">
        <span class="texto">Catálogo</span>
      </button>
        <h2>o también puedes: <h2>
        <button class="b1" onclick="window.location.href='register.php'">Registrarte</button> 
        <button class="b1" onclick="window.location.href='login.php'">Iniciar sesión</button>
       </p>
    <?php endif; ?>
   
   </body>
  </html>
