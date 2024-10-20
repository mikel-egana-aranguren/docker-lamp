<?php
    include 'databaseConnect.php';
    $konexioa = konektatuDatuBasera();

    function datuakSartuDatuBasean($titulua, $egilea, $prezioa, $mota, $urtea){
        //$mysqli = sortuMysqli();
       // $sql = "INSERT INTO bideojokoak (titulu, egilea, prezioa, mota, deskripzioa, urtea)
        //        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare("INSERT INTO bideojokoa (titulu, egilea, prezioa, mota, deskripzioa, urtea);
                VALUES (?, ?, ?, ?, ?)");
        //$stmt->bind_param('ssdss', $titulua, $egilea, $prezioa, $mota, $urtea);
        $stmt->bind_param("ssdss", $titulua, $egilea, $prezioa, $mota, $urtea);
        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }
        $stmt->close();

    }
    function datuakAldatu($titulua, $egilea, $prezioa, $mota, $urtea){
        $mysqli = sortuMysqli();
        $sql = "UPDATE bideojokoak
                SET titulu=?, egilea=?, prezioa=?, mota=?, deskripzioa=?, urtea=?
                WHERE ISBN = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('ssdss', $titulua, $egilea, $prezioa, $mota, $urtea);
        $stmt->execute();
        if (mysqli_stmt_errno($stmt) === 0) {
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }
    }
    if(isset($_POST['gehitu'])){
        $titulu = $_POST['gehituTitulua'];
        $egilea = $_POST['gehituEgilea'];
        $prezioa = $_POST['gehituPrezioa'];
        $mota = $_POST['gehituMota'];
        $urtea = $_POST['gehituUrtea'];
        datuakSartuDatuBasean($titulu, $egilea, $prezioa, $mota, $urtea);
    }
    else if(isset($_POST['aldatu'])){
        $titulu = $_POST['aldatuTitulua'];
        $egilea = $_POST['aldatuEgilea'];    
        $prezioa = $_POST['aldatuPrezioa'];
        $mota = $_POST['aldatuMota'];
        $deskripzioa = $_POST['aldatuDeskripzioa'];
        $urtea = $_POST['aldatuUrtea'];
        datuakAldatu($titulu, $egilea, $prezioa, $mota, $urtea);
    }
    
    $konexioa->close();
?>