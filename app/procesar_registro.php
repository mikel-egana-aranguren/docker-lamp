<?php
require 'conexion.php'; // Archivo para conectarse a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellidos = $_POST['apellidos'];
    $dni = $_POST['dni'];
    $telefono = $_POST['telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $email = $_POST['email'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT); // Encriptar contraseña

    // Validar formato DNI
    if (validar_dni($dni)) {
        $sql = "INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, nombre_usuario, contrasena)
                VALUES ('$nombre', '$apellidos', '$dni', '$telefono', '$fecha_nacimiento', '$email', '$nombre_usuario', '$contrasena')";
        if ($conn->query($sql) === TRUE) {
            echo "Usuario registrado correctamente";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "DNI inválido";
    }
}

function validar_dni($dni) {
    $letras = "TRWAGMYFPDXBNJZSQVHLCKE";
    $numero = substr($dni, 0, 8);
    $letra = substr($dni, 9, 1);
    return $letra === $letras[$numero % 23];
}
?>
