<?php
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
            header("Location: index.php");
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
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }  
        $stmt->close();
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $akzioa = $_POST["akzioa"];
        if($akzioa === "gehitu"){
        $titulu = $_POST['titulua'];
        $egilea = $_POST['egilea'];
        $prezioa = $_POST['prezioa'];
        $mota = $_POST['mota'];
        $urtea = $_POST['argitaratze_urtea'];
        datuakSartuDatuBasean($conn, $titulu, $egilea, $prezioa, $mota, $urtea);
    }
        else if($akzioa === "aldatu"){
        $titulu = $_POST['aldatuTitulua'];
        $egilea = $_POST['aldatuEgilea'];    
        $prezioa = $_POST['aldatuPrezioa'];
        $mota = $_POST['aldatuMota'];
        $deskripzioa = $_POST['aldatuDeskripzioa'];
        $urtea = $_POST['aldatuUrtea'];
        datuakAldatu($conn, $titulu, $egilea, $prezioa, $mota, $urtea);
    }}
    
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
            <button onclick="window.location.href='index.php'">Atzera</button>
        </div>
    </body>
</html>