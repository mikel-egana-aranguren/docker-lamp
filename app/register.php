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
    $conn = konektatuDatuBasera();

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $NAN = $_POST['nan'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $jaiotzeData = str_replace('/', '-', $jaiotzeData);
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitz'];


    $hashed_pasahitza = password_hash($pasahitza, PASSWORD_BCRYPT);

	
    if (NANaBalida($NAN)){
	    $stmt = $conn->prepare("INSERT INTO ERABILTZAILEA (izena, abizena, NAN, pasahitza, telefonoa, jaiotzeData, email) VALUES (?,?,?,?,?,?,?)");
	
	    if($stmt==false){
		    echo "Error: " . $conn->error;
	    }
        else{
            $stmt->bind_param("sssssss", $izena, $abizena, $NAN, $hashed_pasahitza, $telefonoa, $jaiotzeData, $email);
	
	        if($stmt->execute()){
		        echo "Pertsona honen datuak gorde dira";
	        }else{
		        echo "Error: " . $stmt->error;
	        }
            $stmt->close();
        }
	
     }else{

	echo "NAN-a txarto";
     }


}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <button id= "index_botoia" onclick="window.location.href='index.php'" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Hasiera</button>
        
        <hr>
    </header>
    <div class="register_form">
        
        <h1 class="titulua">Erregistro-formularioa</h1>

        <form id ="register_form" action="register.php" method="post">

            <div class="input-box">
                <label for="izena">Izena</label>
                <input type="text" name="izena" id="izena" placeholder= "Sartu zure izena hemen" required >
            </div>
            <div class="input-box">
                <label for="abizena">Abizena</label>
                <input type="text" name="abizena" id="abizena" placeholder= "Sartu zure abizena hemen" required >
            </div>

            <div class="input-box">
                <label for="pasahitza">Pasahitza</label>
                <input type="password" name="pasahitza" id="pasahitza" required >
            </div>

            <div class="input-box">
                <label for="nan">NAN</label>
                <input type="text" name="nan" id="nan" placeholder= "11111111-Z" pattern= "[0-9]{8}-[A-Z]" required >
            </div>

            <div class="input-box">
                <label for="telefonoa">Telefonoa</label>
                <input type="tel" name="telefonoa" id="telefonoa" placeholder= "123456789" pattern="[0-9]{9}" required>
            </div>
            
            <div class="input-box">
                <label for="jaiotzeData">Jaiotze data</label>
                <input type="date" name="jaiotzeData" id="jaiotzeData" required>
            </div>

            <div class="input-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder= "adibidea@zerbitzaria.extentsioa" required>
            </div><br>

            
            <button id= "register_submit" type="submit" onclick="window.location.href='index.php'" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Gorde</button>
            
        </form>
        <button onclick="window.location.href='login.php'" style="font-size: 15px; width: 100px; background-color: rgb(255, 208, 0); border-radius: 10px;">Login</button>
    
    <script src="js/script.js"></script>

    </div>
    
</body>
    
</html>
