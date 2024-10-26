<?php
	include 'databaseConnect.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		$email = $_POST['email'];
		$pasahitza = $_POST['pasahitza'];
	
		$stmt = $conn->prepare("SELECT pasahitza FROM erabiltzailea WHERE email = ?");	
		$stmt->bind_param("s", $email);


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
			
			$passwordOna = password_verify($pasahitza,$userData['pasahitza']);
		
			if($passwordOna) {
				echo "Ongi etorri";
			} else{
				
				echo "Pasahitza okerra";
			}

		}
		$stmt->close();


	}
	$conn->close();
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
<form id="login_form" action="login.php" method="post">
	<label for="email">Email:</label>
	<input type="email" id="email" name="email" placeholder="Sartu zure email-a" required><br>
    	<label for="pasahitza">Pasahitza:</label>
    	<input type="password" id="pasahitza" name="pasahitza" placeholder="Sartu zure pasahitza" required><br>
	
	<br>
	<div class="botoi_container">
		<input id= "login_submit" type="submit" value="Hasi Saioa">
		<input id="atzera_botoia" type="button" value="Atzera" onclick="location.href='index.php'">

	</div>
</form>
<style>
	body{
		background-color: #4f64a5c5;
	}
	.botoi_container{
		display:flex;
		align-items:center;
	}
	#atzera_botoia {
		margin-left: 3cm;
	}
</style>
<script>
    document.getElementById('email').addEventListener('input', function(event) {
        var input = event.target;
        var value = input.value;

        if (value.length > 250) {
            input.value = value.slice(0, 250);
        }
    });
</script>

</body>
</html>

