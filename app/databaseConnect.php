<?php
$hostname = "db";
$username = "admin";
$password = "test";
$database = "database";


//Konexioa sortzen dugu
$conn = mysqli_connect($hostname, $username, $password, $database);


//Konexioa konprobatazen dugu behin sortuta
if(!$conn){
	die("Konexioa galduta: " . mysqli_connect_error());
}
?>
