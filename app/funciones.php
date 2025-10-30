<?php
function validarNIF($nif) {
    $nif = strtoupper(trim($nif));
    if (!preg_match('/^[0-9]{8}-[A-Z]$/', $nif)) { return false; }
    list($numero, $letra) = explode('-', $nif);
    $letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
    $letraCalculada = $letrasValidas[$numero % 23];
    return $letra === $letraCalculada;
}

function validarFecha($fecha, $formato = 'Y-m-d') {
    $d = DateTime::createFromFormat($formato, $fecha);
    return $d && $d->format($formato) === $fecha;
}

function balioztatuErabiltzaileDatuak($data, $egiaztatuPasahitza = true) {
    $errores = [];

    if (!preg_match("/^[a-zA-Z\s]+$/", $data['izen_abizenak'])) { $errores['nombre'] = "Izenak letrak eta hutsuneak soilik izan ditzake."; }
    if (!validarNIF($data['nan'])) { $errores['nan'] = "NANa ez da zuzena."; }
    if (!preg_match("/^[0-9]{9}$/", $data['telefonoa'])) { $errores['telefono'] = "Telefonoak 9 zenbaki zehatz izan behar ditu."; }
    if (!validarFecha($data['jaiotze_data'])) { $errores['fecha'] = "Jaiotze data ez da zuzena."; }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) { $errores['email'] = "Email formatua ez da zuzena."; }

    if ($egiaztatuPasahitza) {
        if (empty($data['pasahitza']) || strlen($data['pasahitza']) < 6) {
            $errores['pass'] = "Pasahitzak gutxienez 6 karaktere izan behar ditu.";
        }
    }
    return $errores;
}

function balioztatuItemDatuak($data) {
    $errores = [];
    if (empty(trim($data['izena']))) { $errores['izena'] = "Izenburua ezin da hutsik egon."; }
    if (!is_numeric($data['prezioa']) || floatval($data['prezioa']) < 0) { $errores['prezioa'] = "Prezioa zenbaki positiboa izan behar da."; }
    if (!ctype_digit($data['stocka']) || intval($data['stocka']) < 0) { $errores['stocka'] = "Stocka zenbaki oso positiboa izan behar da."; }
    return $errores;
}

function egiaztatuElementuJabetza($conn, $item_id, $logged_user_id) {
    $sql_jabea = "SELECT erabiltzaile_id, portada_fitxategia FROM elementuak WHERE id = ?";
    $stmt_jabea = $conn->prepare($sql_jabea);
    $stmt_jabea->bind_param("i", $item_id);
    $stmt_jabea->execute();
    $result_jabea = $stmt_jabea->get_result();
    
    if ($result_jabea->num_rows !== 1) { 
        return ['success' => false, 'error' => 'Ez da elementua aurkitu.'];
    }
    $item_data = $result_jabea->fetch_assoc();
    $stmt_jabea->close();
    
    if ($item_data['erabiltzaile_id'] != $logged_user_id) {
        return ['success' => false, 'error' => 'Ez duzu baimenik elementu hau kudeatzeko.'];
    }
    
    return ['success' => true, 'data' => $item_data];
}

function kudeatuArgazkiIgoera($fileInputName) {
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] == UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'data' => null]; 
    }

    if ($_FILES[$fileInputName]['error'] != UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Errorea gertatu da fitxategia igotzean. Kodea: ' . $_FILES[$fileInputName]['error']];
    }

    $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
    $fileName = $_FILES[$fileInputName]['name'];
    $fileSize = $_FILES[$fileInputName]['size'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    $allowedfileExtensions = ['jpg', 'jpeg', 'png'];
    $allowedMimeTypes = ['image/jpeg', 'image/png'];

    if (!in_array($fileExtension, $allowedfileExtensions) || !in_array(mime_content_type($fileTmpPath), $allowedMimeTypes)) {
        return ['success' => false, 'error' => 'Fitxategi mota ez da onartzen (JPG edo PNG soilik).'];
    }

    if ($fileSize > 2 * 1024 * 1024) { 
        return ['success' => false, 'error' => 'Fitxategia handiegia da (gehienez 2MB).'];
    }
    
    $dest_path = UPLOAD_DIR . $newFileName;
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        return ['success' => true, 'data' => $newFileName]; 
    } else {
        return ['success' => false, 'error' => 'Errorea gertatu da fitxategia zerbitzarira mugitzean.'];
    }
}
?>