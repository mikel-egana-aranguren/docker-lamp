<?php
//inicar sesion php
session_start();
?>

<html>
 <body>
  <?php if (isset($_SESSION['usuario'])): ?>
      <p>
        <a href="show_user.php?user=<?= intval($_SESSION['idU']) ?>"> Mi perfil </a>
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
      ";
      
    echo "<a href='modify_item.php?id={$row['idPelicula']}' style='margin-left: 10px;'>Modificar</a>";
      
     echo "
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

  <!-- Botón para añadir película -->
  <button class='btn-add-pelicula'>Añadir película</button>
  <dialog class='add-peli-dialog'>
    <form id="add_peli_form" method="post" action="add_pelicula.php">
      <label>Título: <input type="text" name="titulo" required></label><br>
      <label>Año: <input type="number" name="anio" required></label><br>
      <label>Director: <input type="text" name="director" required></label><br>
      <label>Género: <input type="text" name="genero" required></label><br>
      <label>Duración: <input type="number" name="duracion" required> minutos</label><br><br>
      <button type="submit">Guardar</button>
      <button type="button" class='btn-cerrar-add-peli'>Cerrar</button>
    </form>
  </dialog>
  
  <script src='./js/validar_pelicula.js'></script>
  <script>
    // Mostrar el diálogo al hacer clic en el botón
    document.querySelector('.btn-add-pelicula').onclick = () => {
      document.querySelector('.add-peli-dialog').showModal();
    };

    // Cerrar el diálogo
    document.querySelector('.btn-cerrar-add-peli').onclick = () => {
      document.querySelector('.add-peli-dialog').close();
    };
  </script>

  <script src='./js/ver_info_peli.js'></script>
 </body>
</html>