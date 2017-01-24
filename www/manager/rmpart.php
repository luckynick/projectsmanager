<?php
	//scrypt for removing user's contribution from project
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	$part_id = (int)$_GET['id'];
	$user = $_SESSION['logged_user'];
	$user_id_a = $conn->query("SELECT `user_id` FROM `users` "
		. "WHERE `username`='$user';")->fetch_assoc();
	$participation_a = $conn->query("SELECT `participant_id` FROM " 
		. "`participations` WHERE `part_id`='$part_id';")->fetch_assoc();
	if($participation_a['participant_id'] == $user_id_a['user_id'])
	//only current user can remove himself from list of contributions
	{
		$conn->query("DELETE FROM `participations` "
			. "WHERE `part_id`='$part_id';");
		header("Location: " . $_SERVER['HTTP_REFERER']);
	}
	else
	{
		echo "Not allowed.";
	}
?>