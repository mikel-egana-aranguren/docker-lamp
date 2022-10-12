<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servicio Web</title>
  <link rel="stylesheet" type="text/css" href="css/headerSets.css">
</head>
<?php include("templates/header.php")?>

<br>
        <div class="tituloproductos"><h1> TODOS LOS PRODUCTOS</h1></div>
		<div class="todosproductos" id="todosproductos">
			
			<br>
            <div class="descripciontodosproductos"><h2 class="descripciontodosproductos2">Para alquilar un vehiculo, contacte con nosotros con el numero de referencia del vehiculo que ha elegido y le contestaremos con la disponibilidad del mismo en la mayor brevedad posible.
            </h2></div>
            <br>
			<ul>
                <li> <h1 class="titulotipos"> AUTOCARAVANAS</h1>
                    <table class="tablaautocaravanas">
                    <tr>
                        <th> Codigo</th>
                        <th> Domicilio</th>
                        <th> Nº Habitantes</th>
                        <th> Extension(m2)</th>
                        <th> Precio</th>
                        <th> Imagen</th>
                    </tr>

<?php
				
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";

    $conn = mysqli_connect($hostname,$username,$password,$db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
                
    $query = mysqli_query($conn, "SELECT * FROM `alojamientos`")
        or die (mysqli_error($conn));
            
    while($mostrar=mysqli_fetch_array($query)){
?>

                    <tr>
                        <td> <?php echo $mostrar['id'] ?></td>
                        <td> <?php echo $mostrar['ubicacion'] ?><</td>
                        <td> <?php echo $mostrar['habitantes'] ?><</td>
                        <td> <?php echo $mostrar['mCuadrados'] ?><</td>
                        <td> <?php echo $mostrar['precio'] ?><</td>
                        <td> <?php echo $mostrar['imagen'] ?><</td>
                    </tr>
    <?php } ?>

                </table></li>

<?php include("templates/footer.php")?>