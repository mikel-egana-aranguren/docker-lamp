<?php

include 'databaseConnect.php';

function NANaBalida($nan) {
	$zenbakiak = substr($nan, 0, 8);
	$letraerabilgarriak="TRWAGMYFPDXBNJZSQVHLCKE";
	$letra = substr($nan, -1);
	$letraKalkulatu = $letraerabilgarriak[$zenbakiak % 23];
	return $letraKalkulatu === $letra;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_GET['email'];
    $password = $_POST['password'];
    $nan = $_POST['NAN'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $jaiotzeData = str_replace('/', '-', $jaiotzeData);
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $newPassword = $_POST['pasahitzaBerria'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $hashedPasahitzaBerria = password_hash($newPassword, PASSWORD_BCRYPT);
    
    $query = "SELECT * FROM ERABILTZAILEA WHERE email = '$id' AND pasahitza = '$hashedPassword'";
    $egiaztatu = mysqli_query($conn, $query);

    if (mysqli_num_rows($egiaztatu) == 1) {

        if (NANaBalida($NAN)){
            $stmt = $conn->prepare("INSERT INTO ERABILTZAILEA (izena, abizena, NAN, pasahitza, telefonoa, jaiotzeData, email) VALUES (?,?,?,?,?,?,?)");
            
            if($stmt==false){
                echo "Error: " . $conn->error;
            }
        
        }
        $updateQuery = "UPDATE ERABILTZAILEA (izena, abizena, NAN, telefonoa, jaiotzeData, pasahitza) SET($izena, $abizena, $nan, $telefonoa, $jaiotzeData, $hashedPasahitzaBerria)  WHERE email = '$email'";
        if (mysqli_query($conn, $updateQuery)) {
            echo "Data updated successfully.";
        } else {
            echo "Error updating data: " . mysqli_error($conn);
        }
    } else {
        echo "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <button id= "index_botoia" onclick="window.location.href='index.php'" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Hasiera</button>
        
        <hr>
    </header>
    <div class="register_form">
        
        <h1 class="titulua">Datuak aldatu</h1>

        <form id ="register_form" action="register.php" method="post">

            <div class="input-box">
                <label for="email">Zure Email-a sartu</label>
                <input type="email" name="email" id="email" placeholder= "Sartu zure email-a hemen" required>
            </div>

            <div class="input-box">
                <label for="pasahitza">Zure Pasahitza sartu</label>
                <input type="password" name="pasahitza" id="pasahitza" placeholder="Sartu zure egungo pasahitza" required >
            </div>

            <div class="input-box">
                <label for="izena">Izen berria</label>
                <input type="text" name="izena" id="izena" placeholder= "Sartu zure izena hemen" required >
            </div>
            <div class="input-box">
                <label for="abizena">Abizen berria</label>
                <input type="text" name="abizena" id="abizena" placeholder= "Sartu zure abizena hemen" required >
            </div>

            <div class="input-box">
                <label for="pasahitzaBerria">Pasahitza berria</label>
                <input type="password" name="pasahitzaBerria" id="pasahitzaBerria" required >
            </div>

            <div class="input-box">
                <label for="nan">NAN berria</label>
                <input type="text" name="nan" id="nan" placeholder= "11111111-Z" pattern= "[0-9]{8}-[A-Z]" required >
            </div>

            <div class="input-box">
                <label for="telefonoa">Telefono berria</label>
                <input type="tel" name="telefonoa" id="telefonoa" placeholder= "123456789" pattern="[0-9]{9}" required>
            </div>
            
            <div class="input-box">
                <label for="jaiotzeData">Jaiotze data berria</label>
                <input type="date" name="jaiotzeData" id="jaiotzeData" required>
            </div>

            
            <button id= "register_submit" type="submit" onclick="window.location.href='index.php'" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Gorde</button>
            
        </form>
        <button onclick="window.location.href='login.php'" style="font-size: 15px; width: 100px; background-color: rgb(255, 208, 0); border-radius: 10px;">Login</button>
    
    <script src="js/script.js"></script>

    </div>
    
</body>
    
</html>
