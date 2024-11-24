<?php
ini_set('display_errors', 0); 
ini_set('log_errors', 1);
ini_set('error_log', 'error.log');
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();
session_regenerate_id(true);

if(empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'databaseConnect.php';

function NANaBalida($nan) {
	$zenbakiak = substr($nan, 0, 8);
	$letraerabilgarriak="TRWAGMYFPDXBNJZSQVHLCKE";
	$letra = substr($nan, -1);
	$letraKalkulatu = $letraerabilgarriak[$zenbakiak % 23];
	return $letraKalkulatu === $letra;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Tokena ez da zuzena";
        exit();
    }

    $id = $_POST['email'];
    $password = $_POST['pasahitza'];
    $nan = $_POST['nan'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotzeData = $_POST['jaiotzeData'];
    $jaiotzeData = str_replace('/', '-', $jaiotzeData);
    $izena = $_POST['izena'];
    $abizena = $_POST['abizena'];
    $newPassword = $_POST['pasahitzaBerria'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $hashedPasahitzaBerria = password_hash($newPassword, PASSWORD_BCRYPT);
    
    $query = "SELECT * FROM erabiltzailea WHERE email = '$id'";
    $egiaztatu = mysqli_query($conn, $query);

    if (mysqli_num_rows($egiaztatu) == 1) {
        $row = mysqli_fetch_assoc($egiaztatu);

        if (password_verify($password, $row['pasahitza'])) {
            if (NANaBalida($nan)) {
                $hashedPasahitzaBerria = password_hash($newPassword, PASSWORD_BCRYPT);

                $updateQuery = "UPDATE erabiltzailea SET 
                    izena = ?, abizena = ?, NAN = ?, telefonoa = ?, jaiotzeData = ?, pasahitza = ?
                    WHERE email = ?";
                
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("sssssss", $izena, $abizena, $nan, $telefonoa, $jaiotzeData, $hashedPasahitzaBerria, $id);

                if ($stmt->execute()) {
                    echo "Datuak aldatu dira.";
                    echo "<script>
                            alert('Pertsona honen datuak gorde dira');
                            window.location.href = 'home.php';
                          </script>";
                } else {
                    echo "Errorea: " . $stmt->error;
                }
                $stmt->close();
            } else {
                echo "NAN okerra.";
            }
        } else {
            echo "Pasahitza okerra.";
        }
    } else {
        echo "Email okerra.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify User</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <button id= "index_botoia" onclick="window.location.href='home.php'" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Hasiera</button>
        
        <hr>
    </header>
    <div class="user_modify_form">
        
        <h1 class="titulua">Datuak aldatu</h1>

        <form id ="user_modify_form" action="modify_user.php" method="post">

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
                <input type="text" name="nan" id="nan" placeholder= "12345678-Z" pattern= "[0-9]{8}-[A-Z]" required >
            </div>

            <div class="input-box">
                <label for="telefonoa">Telefono berria</label>
                <input type="tel" name="telefonoa" id="telefonoa" placeholder= "123456789" pattern="[0-9]{9}" required>
            </div>
            
            <div class="input-box">
                <label for="jaiotzeData">Jaiotze data berria</label>
                <input type="date" name="jaiotzeData" id="jaiotzeData" required>
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>"><br>

            
            <button id= "user_modify_submit" type="submit" style="font-size: 15px; width: 100px; border-radius: 10px; background-color: rgb(207, 2, 248);" >Gorde</button>
            
        </form>
        <button onclick="window.location.href='login.php'" style="font-size: 15px; width: 100px; background-color: rgb(255, 208, 0); border-radius: 10px;">Login</button>
    

    </div>
    
</body>
    
</html>
