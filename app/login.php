<?php
session_start();

$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT email, password,dni FROM usuarios WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $query);

if ($result->num_rows == 1) {
    $_SESSION['loggedIn'] = true;
    $row = $result->fetch_assoc();
    $_SESSION['dniUsuario'] = $row['dni'];
    
    
    header('Location: dashboard.php');
} else {
    echo "Correo o contraseña incorrectos.";
}


mysqli_close($conn);
?>
