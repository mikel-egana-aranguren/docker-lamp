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
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasi Saioa</title>
</head>
<body>


<h2>Hasi Saioa</h2>
<form id="login_form"action="login.php" method="post">
	<label for="erabiltzailea">Erabiltzailea:</label>
	<input type="text" id="erabiltzailea" name="erabiltzailea" placeholder="adiidez: Erabiltzaile123" required><br>
    	<label for="pasahitza">Pasahitza:</label>
    	<input type="password" id="pasahitza" name="pasahitza" placeholder="Sartu zure pasahitza" required><br>
	
	<br>
	<div class="botoi_container">
		<input id= "login_summit" type="summit" value="Hasi Saioa">
		<input id="atzera_botoia" type="button" value="Atzera" onclick="location.href='index.php'">

	</div>
</form>
<style>
	.botoi_container{
		display:flex;
		align-items:center;
	}
	#atzera_botoia {
		margin-left: 3cm;
	}
</style>
<script>
    document.getElementById('erabiltzailea').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

</body>
</html>

