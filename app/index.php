<?php
  echo '<h1 style="text-align: center;"><span style="text-decoration: underline;"><strong>CONCESIONARIO</strong></span></h1>
<div style="text-align: center;"><a href="index.php"><button id="login">Iniciar Sesi&oacute;n</button></a></div>
<div style="text-align: center;">&nbsp;</div>
<div style="text-align: center;"><a href="register.php"><button id="register">Registrarse</button></a></div>
<p>&nbsp;</p>';
  // phpinfo();
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }



$query = mysqli_query($conn, "SELECT * FROM usuarios")
   or die (mysqli_error($conn));

while ($row = mysqli_fetch_array($query)) {
  echo
   "<tr>
    <td>{$row['id']}</td>
    <td>{$row['nombre']}</td>
   </tr>";
   

}

?>
