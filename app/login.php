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
    <style>
	body {
		font-family: Arial, sans-serif;
		background-color: #f4f4f4;
		color: #333
	}
	.form-container {
		width: 60%;
		margin: 0 auto;		
		padding: 20px;
		border: 1px solid #ddd;
		background-color:#f9f9f9;
	}
	.form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Hasi Saioa</h2>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="username">Erabiltzaile izena:</label>
            <input type="text" id="username" name="username" placeholder="adibidea: erabiltzaile123" required>
        </div>
        <div class="form-group">
            <label for="password">Pasahitza:</label>
            <input type="password" id="password" name="password" placeholder="Sartu pasahitza" required>
        </div>
        <div class="form-group">
            <input type="submit" value="Hasi Saioa">
        </div>
    </form>
</div>

</body>
</html>





