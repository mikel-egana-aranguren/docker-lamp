<?php

    ini_set('display_errors', 0); 
	ini_set('log_errors', 1);
	ini_set('error_log', 'error.log');
	ini_set('session.cookie_httponly', 1);
	ini_set('session.cookie_secure', 1);
	ini_set('session.cookie_samesite', 'Strict');

    session_start();
    
    if (!isset($_SESSION['email']) || !$_SESSION['logged_in']|| !isset($_SESSION['logged_in'])) {
        header("Location: login.php");
        exit();
    }

    if ($_SESSION['role'] !== 1) {
        echo "Ez daukazu baimenik hona sartzeko.";
        exit();
    }

    if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

    include 'databaseConnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Tokena ez da zuzena";
        exit();
    }
    
    $titulu = htmlspecialchars(trim($_POST['titulu'])); 
    $egilea = htmlspecialchars(trim($_POST['egilea']));
    $prezioa = floatval($_POST['prezioa']);
    $mota = htmlspecialchars(trim($_POST['mota']));
    $urtea = intval($_POST['urtea']);



    
    if (empty($titulu) || empty($egilea) || empty($prezioa) || empty($mota) || empty($urtea)) {
        echo "Errorea: Datu guztiak bete behar dira.";
        exit();
    }

    
    if (!is_numeric($prezioa) || !is_numeric($urtea)) {
        echo "Errorea: Prezioa eta urtea balio numerikoak izan behar dira.";
        exit();
    }

    
    $query = "SELECT * FROM bideojokoa WHERE titulu = ? AND egilea = ?";
    $stmt = $conn->prepare($query);

    if ($stmt === false) {
        echo "Errorea prestatzen SELECT kontsulta: " . $conn->error;
        exit();
    }

    
    $stmt->bind_param("ss", $titulu, $egilea);
    $stmt->execute();
    $result = $stmt->get_result();

    
    if ($result->num_rows == 1) {
        
        $updateQuery = "UPDATE bideojokoa SET prezioa = ?, mota = ?, urtea = ? WHERE titulu = ? AND egilea = ?";
        $updateStmt = $conn->prepare($updateQuery);

        if ($updateStmt === false) {
            echo "Errorea prestatzen UPDATE kontsulta: " . $conn->error;
            exit();
        }

        
        $updateStmt->bind_param("dssss", $prezioa, $mota, $urtea, $titulu, $egilea);

        
        if ($updateStmt->execute()) {
            session_regenerate_id(true);
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: home.php");
            exit();
        } else {
            echo "Errorea datuak eguneratzean: " . $updateStmt->error;
        }
        $updateStmt->close();
    } else {
        echo "Errorea: Ez da aurkitu bideojokorik izen horrekin.";
    }

   
    $stmt->close();
} 


$conn->close();
?>

<!DOCTYPE html>
<html lang="eu">
<head>
    <script src= "inactivity.js"> </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bideojokoa Aldatu</title>
    <link rel="stylesheet" href="main_style.css">
    <script src="main_script.js"></script>
</head>
<body>
    <h1>Bideojokoa Aldatu</h1>
    <form action="modify_item.php" method="post">
        <label for="titulu">Titulua:</label>
        <input type="text" id="titulu" name="titulu" required><br><br>
        <label for="egilea">Egilea:</label>
        <input type="text" id="egilea" name="egilea" required><br><br>
        <label for="prezioa">Prezioa:</label>
        <input type="number" id="prezioa" name="prezioa" required><br><br>
        <label for="mota">Mota:</label>
        <input type="text" id="mota" name="mota" required><br><br>
        <label for="urtea">Urtea:</label>
        <input type="number" id="urtea" name="urtea" required><br><br>
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"><br>
        <button type="submit">Aldatu</button>

    </form>
    <button onclick="window.location.href='home.php'">Atzera</button>
</body>
<style>
    body{
        background-color: #4f64a5c5;
    }
    </style>
</html>
