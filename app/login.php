<?php
	include 'databaseConnect.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$erabiltzaile = $_POST['erabiltzaile'];
		$pasahitz = $_post['pasahitz'];
	
		$stmt = $conn->prepare("SELECT pasahitz FROM ERABILTZAILEA WHERE erabiltzaile = ?");	
		$stmt->bind_param("s", $erabiltzaile);


		if($stmt === false) { 
			echo "Prepare failed: " . $conn->error;
			
		}

		$stmt->execute();
		$result = $stmt->get_result();

		//Orain konprobatzen dugu erabiltzailea existitzen den


		if($result->num_rows === 0){
			echo "Erabiltzaile honek ez dago erregistratuta";

		} else{
			$userData = $result->fetch_assoc();
			
			$passwordOna = password_verify($pasahitz,$userData['pasahitz']);
		
			if($passwordOna) {
				echo "Ongi etorri";
			} else{
				
				echo "Pasahitza okerra";
			}

		}
		$stmt->close();


	}
?>
