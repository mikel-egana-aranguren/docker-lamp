<?php
require_once('config.php');
require_once('funciones.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = $_SESSION['user_id'];
    $errores = balioztatuItemDatuak($_POST); 
    $portada_fitxategia_gorde = null;

    $uploadResult = kudeatuArgazkiIgoera('portada_fitxategia');
    if (!$uploadResult['success']) {
        if ($uploadResult['data'] !== null) { 
            $errores['portada'] = $uploadResult['data'];
        }
    } else {
        $portada_fitxategia_gorde = $uploadResult['data']; 
    }

    if (count($errores) > 0) {
        $_SESSION['errores_item'] = $errores;
        $_SESSION['old_data_item'] = $_POST;
        header('Location: add_item'); 
        exit;
    } else {
        $sql = "INSERT INTO elementuak (erabiltzaile_id, izena, deskribapena, prezioa, stocka, kategoria, portada_fitxategia) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdiss", $user_id, $_POST['izena'], $_POST['deskribapena'], $_POST['prezioa'], $_POST['stocka'], $_POST['kategoria'], $portada_fitxategia_gorde);

        if ($stmt->execute()) {
            unset($_SESSION['old_data_item']); 
            header('Location: items');
            exit;
        } else {
            $_SESSION['errores_item'] = ['general' => "Errorea datu-basean gordetzean: " . $conn->error];
            $_SESSION['old_data_item'] = $_POST;
            if ($portada_fitxategia_gorde && file_exists(UPLOAD_DIR . $portada_fitxategia_gorde)) {
                unlink(UPLOAD_DIR . $portada_fitxategia_gorde);
            }
            header('Location: add_item');
            exit;
        }
    }
} else {
    header('Location: ./');
    exit;
}
?>