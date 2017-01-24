<?php
	//this scrypt checks if this user is contributor 
	//for page where scrypt is imported
	require_once('base.php');
	
	if(!isset($_SESSION['logged_user']))
	{
		header('Location: \login.php');
	}
	else
	{
		if(!empty($_POST['project_id'])) $project_id = $_POST['project_id'];
		else $project_id = $_GET['id'];
		$result = $conn->query("SELECT `is_checked` FROM `participations` " 
			. "INNER JOIN `users` ON participations.`participant_id`=users.`user_id` " 
			. "WHERE `project_id`='$project_id' AND `username`='" . $_SESSION['logged_user'] . "';")->fetch_assoc();
		$is_checked = (int)$result['is_checked'];
		if($is_checked != 1) //check if user is not just candidate for being contributor
		{
			echo "You are not contributor, action is not allowed.";
			exit;
		}
	}
?>