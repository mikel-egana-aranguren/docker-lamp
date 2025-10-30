<?php
require_once('config.php');
require_once('funciones.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_POST['item_id']) || !ctype_digit($_POST['item_id'])) {
        header('Location: items'); exit;
    }
    $item_id = $_POST['item_id'];
    $logged_user_id = $_SESSION['user_id']; 
    
    $jabetzaResult = egiaztatuElementuJabetza($conn, $item_id, $logged_user_id);
    if (!$jabetzaResult['success']) {
        $_SESSION['errores_item_mod'] = ['general' => $jabetzaResult['error']];
        header('Location: modify_item?item=' . $item_id);
        exit;
    }
    $oraingo_portada = $jabetzaResult['data']['portada_fitxategia'];

    $errores = balioztatuItemDatuak($_POST);
    $portada_berria_fitxategia = null;

    $uploadResult = kudeatuArgazkiIgoera('portada_fitxategia_berria');
    if (!$uploadResult['success']) {
        if ($uploadResult['data'] !== null) { $errores['portada'] = $uploadResult['data']; }
    } else {
        $portada_berria_fitxategia = $uploadResult['data']; 
    }

    if (count($errores) > 0) {
        if ($portada_berria_fitxategia && file_exists(UPLOAD_DIR . $portada_berria_fitxategia)) {
            unlink(UPLOAD_DIR . $portada_berria_fitxategia);
        }
        $_SESSION['errores_item_mod'] = $errores;
        $_SESSION['old_data_item_mod'] = $_POST;
        header('Location: modify_item?item=' . $item_id); 
        exit;
    } else {
        $ezabatu_portada_check = isset($_POST['ezabatu_portada']);
        $portada_finala_gorde = $oraingo_portada; 

        if ($portada_berria_fitxategia) {
            if ($oraingo_portada && file_exists(UPLOAD_DIR . $oraingo_portada)) { unlink(UPLOAD_DIR . $oraingo_portada); }
            $portada_finala_gorde = $portada_berria_fitxategia;
        } elseif ($ezabatu_portada_check) {
            if ($oraingo_portada && file_exists(UPLOAD_DIR . $oraingo_portada)) { unlink(UPLOAD_DIR . $oraingo_portada); }
            $portada_finala_gorde = null;
        }

        $sql = "UPDATE elementuak SET izena=?, deskribapena=?, prezioa=?, stocka=?, kategoria=?, portada_fitxategia=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdissi", $_POST['izena'], $_POST['deskribapena'], $_POST['prezioa'], $_POST['stocka'], $_POST['kategoria'], $portada_finala_gorde, $item_id);

        if ($stmt->execute()) {
            unset($_SESSION['old_data_item_mod']); 
            header('Location: items');
            exit;
        } else {
            $_SESSION['errores_item_mod'] = ['general' => "Errorea datu-basean eguneratzean: " . $conn->error];
            $_SESSION['old_data_item_mod'] = $_POST;
            if ($portada_berria_fitxategia && file_exists(UPLOAD_DIR . $portada_berria_fitxategia)) {
                unlink(UPLOAD_DIR . $portada_berria_fitxategia);
            }
            header('Location: modify_item?item=' . $item_id);
            exit;
        }
    }
} else {
    header('Location: ./');
    exit;
}
?>