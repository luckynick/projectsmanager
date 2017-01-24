<?php
	//scrypt establishes connection with database
	require_once('common.php');
	$conn = new mysqli($config['db']['location'], $config['db']['user'], 
		$config['db']['password'], $config['db']['name']);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 
?>