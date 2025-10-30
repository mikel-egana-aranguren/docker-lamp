<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('config.php');
require_once('funciones.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $errores = balioztatuErabiltzaileDatuak($_POST, true);

    if (empty($errores)) {
        $sql_check = "SELECT id FROM usuarios WHERE email = ? OR nan = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ss", $_POST['email'], $_POST['nan']);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            $errores['general'] = "Emaila edo NANa jada erregistratuta daude sisteman.";
        }
        $stmt_check->close();
    }
    
    if (count($errores) > 0) {
        $_SESSION['errores'] = $errores;
        $_SESSION['old_data'] = $_POST;
        header('Location: register');
        exit; 
    } else {
        $pass_encriptada = password_hash($_POST['pasahitza'], PASSWORD_DEFAULT);
        
        $sql_insert = "INSERT INTO usuarios (nombre, nan, telefono, fecha_nacimiento, email, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssssss", $_POST['izen_abizenak'], $_POST['nan'], $_POST['telefonoa'], $_POST['jaiotze_data'], $_POST['email'], $pass_encriptada);

        if ($stmt_insert->execute()) {
            unset($_SESSION['old_data']);
            unset($_SESSION['errores']);
            header('Location: login?registro=exito');
            exit;
        } else {
            $_SESSION['errores'] = ['general' => "Errorea datu-basean gordetzean: " . $conn->error];
            $_SESSION['old_data'] = $_POST;
            header('Location: register');
            exit;
        }
    }
} else {
    header('Location: ./');
    exit;
}
?>