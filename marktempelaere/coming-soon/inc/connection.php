<?php
require("constants.php");

// specify the DSN (data source name)
$dsn = "mysql:host=".DB_SERVER.";port=3306;dbname=".DB_NAME;
try {
	// get a database connection
	$conn = new PDO($dsn, DB_USER, DB_PASS);
}
catch (PDOException $exception) {
	// show an error message when connection failed
	$warning[] = "Error while creating the database connection.";
	die();
}
?>