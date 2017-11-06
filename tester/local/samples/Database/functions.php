<?php

function myQuery($connection, $sql, $message) {
	if (isset($connection) && $connection->query($sql) === TRUE) {
		echo $message;
	} else {
		echo "Error: " . $sql . "<br>" . $connection->error;
	}	
}