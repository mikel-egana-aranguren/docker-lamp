<?php

?>
<!DOCTYPE html>
<html>
<head>
    <title>Erabiltzailearen datuak aldatu</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>Erabiltzailearen datuak aldatu</h1>
    <form action="modify_user.php" method="post">
        <label for="izena">Izena:</label><br>
        <input type="text" id="izena" name="izena" required><br>
        <label for="abizena">Abizena:</label><br>
        <input type="text" id="abizena" name="abizena" required><br>
        
        <label for="pasahitz">Pasahitza:</label><br>
        <input type="password" id="pasahitz" name="pasahitz" required><br>
        <input type="submit" value="Aldatu">
    </form>
</body>
</html>
