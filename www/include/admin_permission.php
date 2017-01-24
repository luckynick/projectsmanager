<?php
	//this scrypt checks if this user is admin 
	//for page where scrypt is imported
	require_once('base.php');
	
	if(!isset($_SESSION['logged_user']))
	{
		header('Location: \login.php');
	}
	else
	{
		$is_admin = $conn->query("SELECT `is_admin` FROM `users` " 
		. "WHERE `username`='" . $_SESSION['logged_user'] . "';")->fetch_assoc();
		$is_admin = (int)$is_admin['is_admin'];
		if($is_admin != 1)
		{
			echo "You are not admin, action is not allowed.";
			exit;
		}
	}
?>