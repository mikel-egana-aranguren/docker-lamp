<?php
  echo '<form id="registerForm">
<h1 style="color: #5e9ca0;"><span style="text-decoration: underline; color: #003366;">Registrarse</span></h1>
<p style="text-align: justify; padding-left: 40px;"><label>Usuario:</label> <input id="user" name="user" required="" type="text" /></p>
<p style="text-align: justify; padding-left: 40px;"><label>Contrase&ntilde;a:</label> <input id="user" name="user" required="" type="text" /></p>
<p id="mensaje" style="text-align: justify; padding-left: 40px;"></p>
</form>';
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
