<?php
	//scrypt checks if user is logged in
	require_once('common.php');
	require_once('base.php');
	if(!isset($_SESSION['logged_user']))
	{
		header('Location: \login.php');
	}
	$usr = $conn->query("SELECT `username` FROM `users` WHERE `username`='" 
	. $_SESSION['logged_user'] . "';")->fetch_assoc();
	if($usr['username'] !== $_SESSION['logged_user']) require_once(_HOME . "\logout.php")
?>