<?php
//inicar sesion php
session_start();
?>

<html>
 <body>
  <br><h1 align="center">CATÁLOGO: </h1><br>
  <?php
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";

    //conectarse a la db
    $conn = mysqli_connect($hostname,$username,$password,$db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    //obtener informacion de todas las pelis
    $query = mysqli_query($conn, "SELECT * FROM pelicula")
    or die (mysqli_error($conn));

    //para cada peli mostrar el nombre (a modo de boton) y fecha
    //Agregar a los botones la clase correspondiente para que hagan su funcion definida en el js
    //Mostrar un popup mediante "dialog", solo si se hace click en el boton
    while ($row = mysqli_fetch_array($query)) {
    echo "
    <p align='center'>
      <button class='btn-ver-info-peli'>{$row['titulo']}</button>
      <td>({$row['anio']})</td><br><br>
      <dialog class='info-peli'>
       <td>Título: {$row['titulo']}</td><br>
       <td>Año: ({$row['anio']})</td><br>
       <td>Director: {$row['director']}</td><br>
       <td>Género: {$row['genero']}</td><br>
       <td>Duración: {$row['duracion']} minutos</td><br><br>
       <button class='btn-cerrar-info-peli'>Cerrar</button>
      </dialog>
    </p>
    ";
    }
  ?>
  <a href="index.php" class="button">Volver a inicio</a>
  <script src='./js/ver_info_peli.js'></script>
 </body>
</html>