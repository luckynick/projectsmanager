<?php
	//scrypt for removing projects
	require_once('..\include\admin_permission.php');
	require_once('..\include\base.php');
	$project_id = (int)$_GET['id'];
	$conn->query("DELETE FROM `projects` "
		. "WHERE `id`='$project_id';");
	$conn->query("DELETE FROM `participations` "
		. "WHERE `project_id`='$project_id';");
	delete_dir(_HOME . project_res_path("\proj_$project_id\\"));
?>
Project was removed.<br>
<a href='\'>Main page</a>