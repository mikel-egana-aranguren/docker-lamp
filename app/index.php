<html>
   <head>
    <link rel="stylesheet" type="text/css" href="inicioStyle.css">
   </head>
   <body>
    <h1>¡Bienvenid@ a SafeFilms!<h1>
    <h2>Consulta nuestro catálogo con lo mejor del cine: <h2>
    <form>
     <button class="ctlg">Catálogo</button>
     <button class="inic">Iniciar sesión</button>
     <button class="reg">Registrarse</button>
    </form>
   </body>
  </html>
  
<?php
  
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
