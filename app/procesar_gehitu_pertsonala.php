<?php

require_once('config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}


if (!isset($_GET['item']) || !ctype_digit($_GET['item'])) {
    header('Location: items');
    exit;
}
$item_id = $_GET['item'];
$user_id = $_SESSION['user_id'];


$sql_check = "SELECT id FROM elementuak WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $item_id);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows == 0) {
    header('Location: items'); 
    exit;
}
$stmt_check->close();


$sql_insert = "INSERT IGNORE INTO bazkide_liburuak (bazkide_id, liburu_id) VALUES (?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("ii", $user_id, $item_id);

if ($stmt_insert->execute()) {
    
    header('Location: items'); 
    
    exit;
} else {
    
    echo "Errorea elementua zerrenda pertsonalera gehitzean: " . $conn->error;
}

$stmt_insert->close();
$conn->close();
?>