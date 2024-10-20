<?php

/*function konektatuDatuBasera(){
	$hostname = "db";
	$username = "admin";
	$password = "test";
	$database = "database";

	//Konexioa sortzen dugu
	$conn = mysqli_connect($hostname, $username, $password, $database);

	//Konexioa konprobatazen dugu behin sortuta
	if($conn->connect_error){
		die("Konexioa galduta: " . $conn->connect_error);
	}
	return $conn;
}
function sortuMySqli(){
	$mysqli = new mysqli("db", "admin", "test", "database");
	return $mysqli;
}*/
function konektatuDatuBasera(){
	$hostname = "db";
	$username = "admin";
	$password = "test";
	$database = "database";

	$conn = new mysqli($hostname, $username, $password, $database);

	if(!$conn){
		die("Konexioa galduta: " . $conn->connect_error);
	}
	return $conn;
}
?>
