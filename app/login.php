<?php

	ini_set('display_errors', 0); 
	ini_set('log_errors', 1);
	ini_set('error_log', 'error.log');
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_secure', 1);
	ini_set('session.cookie_samesite', 'Strict');
	session_start();
	session_regenerate_id(true);

	//ini_set('session.cookie_httponly', '1');
	//ini_set('session.cookie_samesite', 'Lax');

	// CSRF tokena sortu
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	include 'databaseConnect.php';
	
	if($_SERVER['REQUEST_METHOD'] == 'POST') {

		// CSRF tokena konprobatu
		if(!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
			echo "Tokena ez da zuzena";
			exit();
		}

		$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
		if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Email okerra";
			exit();
		}
		$pasahitza = $_POST['pasahitza'];
	
		$stmt = $conn->prepare("SELECT pasahitza, role FROM erabiltzailea WHERE email = ?");	
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
				$_SESSION['email'] = $email;
				$_SESSION['role'] = $userData['role'];
				$_SESSION['logged_in'] = true;
				// CSRF tokena berria sortu
				$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

				header("Location: home.php");
				exit();

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
	<script src= "inactivity.js"> </script>
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

	<!-- CSRF tokena -->
	<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"><br>
	
	<br>
	<div class="botoi_container">
		<input id= "login_submit" type="submit" value="Hasi Saioa">
		<input id="register_button" type="button" value="Erregistratu" onclick="location.href='register.php'">

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

