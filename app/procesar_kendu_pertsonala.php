<?php

require_once('config.php');
if (!isset($_SESSION['user_id'])) {
    header('Location: login');
    exit;
}

//  Lortu elementuaren IDa URLtik
if (!isset($_GET['item']) || !ctype_digit($_GET['item'])) {
    header('Location: modify_user'); // Profilera itzuli ID okerra bada
    exit;
}
$item_id = $_GET['item'];
$user_id = $_SESSION['user_id'];


$sql = "DELETE FROM bazkide_liburuak WHERE bazkide_id = ? AND liburu_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $item_id);

if ($stmt->execute()) {
    
    header('Location: modify_user');
    exit;
} else {
    
    echo "Errorea elementua zerrenda pertsonaletik kentzean: " . $conn->error;
    
}

$stmt->close();
$conn->close();
?>