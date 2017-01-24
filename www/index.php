<?php
	require_once('include\common.php');
	if(!empty($_SESSION['logged_user'])) //go to main page if logged in
		header('Location: \manager\index.php');
?>

<html>
	<head>
		<title>Projects Manager</title>
		<link href="include\style.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
	<?php require_once('include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		<a href="login.php">Login<a><br>
		<a href="register.php">Register<a>
	</div>
	</body>
</html>