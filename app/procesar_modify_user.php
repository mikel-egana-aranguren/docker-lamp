<?php
require_once('config.php');
require_once('funciones.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login'); 
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errores = balioztatuErabiltzaileDatuak($_POST, false);
    
    $actualizar_pass = false;
    if (!empty($_POST['pasahitza_nueva'])) {
        if (strlen($_POST['pasahitza_nueva']) < 6) { $errores['pass'] = "Pasahitz berriak gutxienez 6 karaktere izan behar ditu."; }
        if ($_POST['pasahitza_nueva'] !== $_POST['pasahitza_repetir']) { $errores['pass_rep'] = "Pasahitzak ez datoz bat."; }
        $actualizar_pass = true;
    }

    if (empty($errores)) {
        $sql_check = "SELECT id FROM usuarios WHERE (email = ? OR nan = ?) AND id != ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("ssi", $_POST['email'], $_POST['nan'], $user_id); 
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $errores['general'] = "Emaila edo NANa beste erabiltzaile batek erregistratuta dauka.";
        }
        $stmt_check->close();
    }
    
    if (count($errores) > 0) {
        $_SESSION['errores_perfil'] = $errores;
        $_SESSION['old_data_perfil'] = $_POST;
        header('Location: modify_user');
        exit;
    } else {
        if ($actualizar_pass) {
            $pass_encriptada = password_hash($_POST['pasahitza_nueva'], PASSWORD_DEFAULT);
            $sql_update = "UPDATE usuarios SET nombre=?, nan=?, telefono=?, fecha_nacimiento=?, email=?, password=? WHERE id=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ssssssi", $_POST['izen_abizenak'], $_POST['nan'], $_POST['telefonoa'], $_POST['jaiotze_data'], $_POST['email'], $pass_encriptada, $user_id);
        } else {
            $sql_update = "UPDATE usuarios SET nombre=?, nan=?, telefono=?, fecha_nacimiento=?, email=? WHERE id=?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssssi", $_POST['izen_abizenak'], $_POST['nan'], $_POST['telefonoa'], $_POST['jaiotze_data'], $_POST['email'], $user_id);
        }

        if ($stmt_update->execute()) {
            $_SESSION['user_nombre'] = $_POST['izen_abizenak'];
            header('Location: modify_user?exito=1');
            exit;
        } else {
            $_SESSION['errores_perfil'] = ['general' => "Errorea datu-basean eguneratzean: " . $conn->error];
            $_SESSION['old_data_perfil'] = $_POST;
            header('Location: modify_user'); 
            exit;
        }
    }
} else {
    header('Location: ./');
    exit;
}
?>