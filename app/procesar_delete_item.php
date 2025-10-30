<?php
require_once('config.php');
require_once('funciones.php'); 

if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['item_id']) && ctype_digit($_POST['item_id'])) {
    
    $item_id = $_POST['item_id'];
    $logged_user_id = $_SESSION['user_id'];

    $jabetzaResult = egiaztatuElementuJabetza($conn, $item_id, $logged_user_id);
    if (!$jabetzaResult['success']) {
        echo "Errorea: " . $jabetzaResult['error'];
        exit;
    }
    
    $oraingo_portada = $jabetzaResult['data']['portada_fitxategia'];

    $sql = "DELETE FROM elementuak WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);

    if ($stmt->execute()) {
        if ($oraingo_portada && file_exists(UPLOAD_DIR . $oraingo_portada)) {
            unlink(UPLOAD_DIR . $oraingo_portada);
        }
        header('Location: items');
        exit;
    } else {
        echo "Errorea elementua ezabatzean: " . $conn->error;
    }

    $stmt->close();
    $conn->close();

} else {
    header('Location: items');
    exit;
}
?>