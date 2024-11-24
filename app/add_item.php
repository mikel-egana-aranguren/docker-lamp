<?php

    ini_set('display_errors', 0); 
    ini_set('log_errors', 1);

    session_start();
    session_regenerate_id(true);

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

    function datuakSartuDatuBasean($conn, $titulu, $egilea, $prezioa, $mota, $urtea){
        //$mysqli = sortuMysqli();
       // $sql = "INSERT INTO bideojokoak (titulu, egilea, prezioa, mota, deskripzioa, urtea)
        //        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare("INSERT INTO bideojokoa (titulu, egilea, prezioa, mota, urtea)
                VALUES (?, ?, ?, ?, ?)");
        if ($stmt == false) {
            echo "Errorea datu basearekin: " . $conn->error;
        }
        $stmt->bind_param("ssdss", $titulu, $egilea, $prezioa, $mota, $urtea);
        if ($stmt->execute()) {
            header("Location: home.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }
        $stmt->close();

    }
    function datuakAldatu($conn, $titulu, $egilea, $prezioa, $mota, $urtea){
       $stmt = $conn->prepare("UPDATE bideojokoa
                SET titulu='$titulu', egilea='$egilea', prezioa='$prezioa', mota='$mota', urtea='$urtea'
                WHERE titulu='$titulu' AND egilea='$egilea'");
        if ($stmt == false) {
            echo "Errorea datu basearekin: " . $conn->error;
        }
        $stmt->bind_param("ssdss", $titulu, $egilea, $prezioa, $mota, $urtea);
        if ($stmt->execute()) {
            header("Location: home.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }  
        $stmt->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $titulu = $_POST['titulua'];
        $egilea = $_POST['egilea'];
        $prezioa = $_POST['prezioa'];
        $mota = $_POST['mota'];
        $urtea = $_POST['argitaratze_urtea'];
        datuakSartuDatuBasean($conn, $titulu, $egilea, $prezioa, $mota, $urtea);
    }
    
    
    $conn->close();
?>
<DOCTYPE html>
<html>
    <head>
        <title>GORDE</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <div class="errorea">
            <h1>Gorde</h1>
            <p>Sartutako datuak gorde dira</p>
            <button onclick="window.location.href='home.php'">Atzera</button>
        </div>
    </body>
</html>