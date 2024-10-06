<?php
    include 'databaseConnect.php';
    $konexioa = konektatuDatuBasera();

    function datuakSartuDatuBasean($titulua, $prezioa, $mota, $deskripzioa, $urtea){
        $mysqli = sortuMysqli();
        $sql = "INSERT INTO bideojokoak (titulua, prezioa, mota, deskripzioa, urtea)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sdsi', $titulua, $prezioa, $mota, $deskripzioa, $urtea);
        $stmt->execute();
        if ($stmt->affected_rows === 1) {
            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            echo "Errorea datuak gordetzean";
        }
    }
    if(isset($_POST['gehitu'])){
        $titulua = $_POST['gehituTitulua'];
        $prezioa = $_POST['gehituPrezioa'];
        $mota = $_POST['gehituMota'];
        $deskripzioa = $_POST['gehituDeskripzioa'];
        $urtea = $_POST['gehituUrtea'];
        datuakSartuDatuBasean($titulua, $prezioa, $mota, $deskripzioa, $urtea);
    }
    $konexioa->close();
?>