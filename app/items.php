<?php
//inicar sesion php
session_start();
?>

<html>
 <body>
  <?php if (isset($_SESSION['usuario'])): ?>
      <p>
        <a href="show_user.php?user=<?= intval($_SESSION['idU']) ?>"> Mi perfil </a>?>
        <?php $ruta="items.php"; ?>
      </p>
  <?php else: ?>
    <?php $ruta="index.php"; ?>
    <a href="<?= $ruta ?>" class="button">Volver a inicio</a>
    <?php endif; ?>

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
    <div style='text-align: center; margin-bottom: 1em;'>
      <button class='btn-ver-info-peli'>{$row['titulo']}</button>
      <span style='margin: 0 10px;'>({$row['anio']})</span>

      <a href='modify_item.php?id={$row['idPelicula']}' style='margin-left: 10px;'>Modificar</a>

      <dialog class='info-peli'>
       <td>Título: {$row['titulo']}</td><br>
       <td>Año: ({$row['anio']})</td><br>
       <td>Director: {$row['director']}</td><br>
       <td>Género: {$row['genero']}</td><br>
       <td>Duración: {$row['duracion']} minutos</td><br><br>
       <button class='btn-cerrar-info-peli'>Cerrar</button>
      </dialog>
    </div>
    ";
    }
  ?>
  <script src='./js/ver_info_peli.js'></script>
 </body>
</html>