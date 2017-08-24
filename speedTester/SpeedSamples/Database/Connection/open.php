<?php

$servername = "localhost";
$username = "root";
$password = "root";
$database = "php";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function myQuery($connection, $sql, $message) {
	if (isset($connection) && $connection->query($sql) === TRUE) {
		echo $message;
	} else {
		echo "Error: " . $sql . "<br>" . $connection->error;
	}	
}