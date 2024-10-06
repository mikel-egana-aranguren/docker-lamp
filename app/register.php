<?php

include 'databaseConnect.php';

function NANaBalida($nan) {
	$zenbakiak = substr($nan, 0, 8);
	$letraerabilgarriak="TRWAGMYFPDXBNJZSQVHLCKE"
	$letra = substr($nan, -1);
	$letraKalkulatu = $letraerabilgarriak[$zenbakiak % 23];
	return $letraKalkulatu === $letra;
}

if 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = konektatuDatuBasera();
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $NAN = $_POST['nan'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $jaiotzeData = str_replace('/', '-', $jaiotzeData);
    $email = $_POST['email'];
    $pasahitza = $_POST['pasahitz'];


    $hashed_pasahitza = password_hash($pasahitza, PASSWORD_BCRYP);

	
    if (NANaBalida($NAN)){
	$a = $conn->prepare("INSERT INTO ERABILTZAILEA (izena, abizena, NAN, pasahitza, telefonoa, jaiotzeData, email) VALUES (?,?,?,?,?,?,?)");
	
	if($a==false){
		echo "Error: " . $conn->error;
	}

	$stmt->bind_param("aaaaa", $izena, $abizena, $NAN, $pasahitza, $telefonoa, $jaiotzeData, $email);
	
	if($stmt->execute()){
		echo "Pertsona honen datuak gorde dira";
	}else{
		echo "Error: " . $stmt->error;
	}
	
	$stmt->close();
	
	/*$stmt = $conn->prepare("INSERT INTO ERABILTZAILEAK (erabiltzaile, pasahitz, NAN) VALUES (?, ?, ?)");
    	if ($stmt === false) {
            echo "Prepare failed: " . $conn->error;
        }
        
        $stmt->bind_param("sss", $erabiltzailea, $hashed_pasahitza, $NAN);
    
        
        if ($stmt->execute()) {
            echo " Erabiltzaile eta pasahitza gorde dira!";
        } else {
            echo "Error: " . $stmt->error;
        }
    */
        
        $stmt->close();
     }else{

	echo "NAN-a txarto";
     }


}
?>
