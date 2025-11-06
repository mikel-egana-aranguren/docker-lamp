<?php
//inicar sesion php
session_start();
    // generar un token CSRF si no existe
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
?>

<html>
  <head>
    <link rel="stylesheet" type="text/css" href="inicioStyle.css">
   </head>
 <body class="items">
  <?php if (isset($_SESSION['usuario'])): ?>
      <p>
        <a class="miperf"href="show_user.php?user=<?= intval($_SESSION['idU']) ?>"> <img src='img/miperfilgato.png' style='width:70px; height: 70px; vertical-align:middle;'>Mi perfil</a>
        <?php $ruta="items.php"; ?>
      </p>
  <?php else: ?>
    <?php $ruta="index.php"; ?>
    <a href="<?= $ruta ?>" class="buttonVolver">Volver a inicio</a>
    <?php endif; ?>

  <br><h1 align="center">Catálogo: </h1><br>
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
    <div class='catalog' style='text-align: center; margin-bottom: 1em;'>
      <button class='btn-ver-info-peli'>{$row['titulo']}</button>
      <span style='margin: 0 10px;'>({$row['anio']})</span>
      ";
    if (isset($_SESSION['usuario'])) {
    echo "<a href='modify_item.php?id={$row['idPelicula']}' style='margin-left: 10px;' class='btn-modificar'>
    <img src='img/modificar.png' alt='Modificar' style='width:24px; height:24px; vertical-align:middle;'></a>";
    }
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
<?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
  <!-- Botón para añadir película -->
  <button class='btn-add-pelicula'>Añadir película</button>
  <dialog class='add-peli-dialog'>
    <form class="addPeli"id="add_peli_form" method="post" action="add_pelicula.php">
      <label>Título: <input type="text" name="titulo" required></label><br>
      <label>Año: <input type="number" name="anio" required></label><br>
      <label>Director: <input type="text" name="director" required></label><br>
      <label>Género: <input type="text" name="genero" required></label><br>
      <label>Duración: <input type="number" name="duracion" required> minutos</label><br><br>
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
      <button class="guardar"type="submit">Guardar</button>
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
<?php endif; ?>  
  <script src='./js/ver_info_peli.js'></script>
 </body>
</html>