<?php
session_start(); //iniciar sesion con php
/**Algo de explicación: 
 * Estructura:
    - html: crea el formulario, cuando se le da al botón el .js está "escuchando" y 
    se encarga de validar los datos rollo formato y tal, cosas q hemos definido al crear la bd.
    - php: una vez validados los datos, se envían al php, que se encarga de comprobar si ya existe
    el usuario en la bd y si no existe, lo crea.
    + name del input (html) y el índice de $_POST (php) deben coincidir 
    + primero debe ir la parte de php y luego la de html porq la primera línea debe ser <?php sesion_start
    
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

$query = mysqli_query($conn, "SELECT * FROM pelicula")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<table border='1'>
     <tr>
      <td>{$row['titulo']}</td>
      <td>{$row['duracion']}</td>
     </tr>
    </table>";
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
      <button onclick="window.location.href='register.php'">Registrarse</button> 
    <h2>Consulta nuestro catálogo con lo mejor del cine: <h2>
    <form>
     <button class="ctlg">Catálogo</button>
     <button class="inic">Iniciar sesión</button>
    </form>
   </body>
  </html>
