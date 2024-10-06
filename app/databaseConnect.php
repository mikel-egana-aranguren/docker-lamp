<?php

function konektatuDatuBasera(){
	$hostname = "db";
	$username = "admin";
	$password = "test";
	$database = "database";

	//Konexioa sortzen dugu
	$conn = new mysqli($hostname, $username, $password, $database);

	//Konexioa konprobatazen dugu behin sortuta
	if($conn->connect_error){
		die("Konexioa galduta: " . $conn->connect_error);
	}
	return $conn;
}
function sortuMySqli(){
	$mysqli = new mysqli("db", "admin", "test", "database");
	return $mysqli;
?>
