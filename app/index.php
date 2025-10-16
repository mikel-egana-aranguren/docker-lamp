<?php
session_start(); //iniciar sesion con php
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
   <body>

    <!-- 🔽 Añadido: botón 'Mi perfil' solo si hay sesión activa -->
    <?php if (isset($_SESSION['user_id'])): ?>
      <p>
        <a href="show_user.php?user=<?= intval($_SESSION['user_id']) ?>">Mi perfil</a>
      </p>
    <?php endif; ?>
    <!-- 🔼 Fin del añadido -->

    <h1>¡Bienvenid@ a SafeFilms!<h1>
    <button class="reg" onclick="window.location.href='register.php'">Registrarse</button> 
    <h2>Consulta nuestro catálogo con lo mejor del cine: <h2>
    <button class="ctlg" onclick="window.location.href='catalog.php'">Catálogo</button>
    <button class="inic">Iniciar sesión</button>
   </body>
  </html>
