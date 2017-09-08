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

$sql = "SELECT * FROM Emails";
$result = $conn->query($sql);
//var_dump($result->fetch_all());

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - E-mail: " . $row["email"]. " - Created " . $row["date_created"]. "<br>";
    }
} else {
    echo "0 results";
}

$conn->close();
