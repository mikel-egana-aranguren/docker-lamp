<?php

include 'databaseConnect.php';

function NANaBalida($nan) {
	$zenbakiak = substr($nan, 0, 8);
	$letraerabilgarriak="TRWAGMYFPDXBNJZSQVHLCKE"
	$letra = substr($nan, -1);
	$letraKalkulatu = $letraerabilgarriak[$zenbakiak % 23];
	return $letraKalkulatu === $letra;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $izenAbizenak = $_POST['izenAbizenak'];
    $NAN = $_POST['NAN'];
    $telefono = $_POST['telefono'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $email = $_POST['email'];
    $erabiltzaile = $_POST['erabiltzaile'];
    $pasahitz = $_POST['pasahitz'];


    $hashed_pasahitza = password_hash($pasahitz, PASSWORD_BCRYP);

	
    if (NANaBalida($NAN)){
	$a = $conn->prepare("INSERT INTO PERSONA (izenAbizenak, NAN, telefono, jaiotzeData, email) VALUES (?,?,?,?,?)");
	
	if($a==false){
		echo "Error: " . $conn->error;
	}

	$stmt->bind_param("aaaaa", $izenAbizenak, $NAN, $telefono, $jaiotzeData, $email);
	
	if($stmt->execute()){
		echo "Pertsona honen datuak gorde dira";
	}else{
		echo "Error: " . $stmt->error;
	}
	
	$stmt->close();
	
	$stmt = $conn->prepare("INSERT INTO ERABILTZAILEAK (erabiltzaile, pasahitz, NAN) VALUES (?, ?, ?)");
    	if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }
        
        $stmt->bind_param("sss", $erabiltzailea, $hashed_pasahitza, $NAN);
    
        // Execute the second statement
        if ($stmt->execute()) {
            echo " Erabiltzaile eta pasahitza gorde dira!";
        } else {
            echo "Error: " . $stmt->error;
        }
    
        // Close the second statement
        $stmt->close();
     }else{

	echo "NAN-a txarto";
     }
}
?>
