<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Servicio Web</title>
  <link rel="stylesheet" type="text/css" href="css/headerSets.css">
  <link rel="stylesheet" type="text/css" href="css/logStyle.css">
</head>
<?php include("templates/header.php")?>

  <section class="form-register">
      <h4>Inicia Sesión</h4>
      <input class="control" type="text" name="nombre" id="nombre" placeholder="Nombre">
      <input class="control" type="password" name="contasena" id="contasena" placeholder="Contraseña">
      <input class="boton" type="submit" value="Login">
      <p><a href="registro.php">¿No tengo cuenta?</a></p>
  </section>

<?php include("templates/footer.php")?>