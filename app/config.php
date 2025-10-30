<?php
session_start();

define('UPLOAD_DIR', __DIR__ . '/uploads/portadas/'); 

$hostname = "db";       
$username = "admin";    
$password = "test";     
$db = "database"; 

$conn = new mysqli($hostname, $username, $password, $db);

if ($conn->connect_error) {
  die("Konexio errorea: " . $conn->connect_error);
}
$conn->set_charset("utf8");
?>