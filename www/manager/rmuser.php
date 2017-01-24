<?php
	//scrypt for removing users
	require_once('..\include\admin_permission.php');
	require_once('..\include\base.php');
	$user_id = (int)$_GET['id'];
	$conn->query("DELETE FROM `users` "
		. "WHERE `user_id`='$user_id';");
	$conn->query("DELETE FROM `participations` "
		. "WHERE `participant_id`='$user_id';");
	delete_dir(_HOME . user_res_path("\user_$user_id\\"));
?>
User was removed.<br>
<a href='\'>Main page</a>