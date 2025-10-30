<?php

require_once('config.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $email = $_POST['email'];
    $pass_formulario = $_POST['pasahitza'];

    
    
    $sql = "SELECT id, nombre, email, password FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    
    if ($resultado->num_rows == 1) {
        
        
        $usuario = $resultado->fetch_assoc();
        
        
        
        if (password_verify($pass_formulario, $usuario['password'])) {
            
            session_regenerate_id(true); 
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_nombre'] = $usuario['nombre'];
            
            
            header('Location: modify_user');
            exit;
            
        } else {
            
            header('Location: login?error=1');
            exit;
        }

    } else {
        
        header('Location: login?error=1');
        exit;
    }

} else {
    
    header('Location: index.php');
    exit;
}
?>