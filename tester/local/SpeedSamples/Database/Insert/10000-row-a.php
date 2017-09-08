<?php
/*
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

$uniqueHash = [];

for($a = 0; $a < 10; $a++) {
	for($b = 0; $b < 1000; $b++) {
		$editionHash = '';

		for($i = 0; $i < 12; $i++) {
			$editionHash .= mt_rand(0, 9);
		}

		if (!in_array($editionHash, $uniqueHash)) {
			$uniqueHash[] = $editionHash;
		}
	}
}

foreach ($uniqueHash as $email) {
	$sql = 'INSERT INTO emails (email, date_created) VALUES (\'' . $email . '@example.com\', NOW())';	
	myQuery($conn, $sql, 'Vloženo');
}

$conn->close();
