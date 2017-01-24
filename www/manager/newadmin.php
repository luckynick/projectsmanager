<?php 
	//scrypt for accepting user to admins.
	//Only admins may add new admins.
	require_once('..\include\base.php');
	require_once('..\include\common.php');
	require_once('..\include\admin_permission.php');
	$user_id = (int)$_GET['id'];
	$is_user_admin = $conn->query("SELECT `is_admin`, `username` FROM " 
		. "`users` WHERE `user_id`='$user_id';")->fetch_assoc();
	$usr = $is_user_admin['username'];
	$is_user_admin = $is_user_admin['is_admin'];
	if($is_user_admin == 1)
	{
		echo "User is admin already.";
	}
	else if($is_user_admin == 0 and $usr)
	{
		$conn->query("UPDATE `users` SET `is_admin`='1' " 
			. "WHERE `user_id`='$user_id';");
		echo "Admin was accepted.";
	}
	else
	{
		echo "Error";
	}
?>



<html>
<head>
</head>

<body>
	<br><a class='blue' href='<?php echo $_SERVER['HTTP_REFERER']; ?>'>
		Return to project</a>
</body>
</html>