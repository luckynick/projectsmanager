<?php
	require_once('include\common.php');
	unset($_SESSION['logged_user']);
	session_destroy();
	header("Location: \\");
?>