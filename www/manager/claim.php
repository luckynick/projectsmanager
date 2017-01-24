<?php
	//make current user a candidate for being contributor
	require_once('..\include\base.php');
	require_once('..\include\common.php');
	require_once('..\include\permission.php');
	$user = $_GET['user']; //username
	$proj_id = $_GET['project_id'];
	$user_id = $conn->query("SELECT `user_id` FROM `users` WHERE " 
		. "`username`='$user';")->fetch_assoc();
	$contributors_num = $conn->query("SELECT COUNT(`part_id`) AS `num` FROM" 
		. " `participations` WHERE `project_id`='$proj_id' AND `is_checked`='1';")->fetch_assoc();
	$did_you_claim = $conn->query("SELECT COUNT(`part_id`) AS `num` FROM" 
		. " `participations` WHERE `project_id`='$proj_id' AND `participant_id`='" 
		. $user_id['user_id'] . "';")->fetch_assoc(); //check if there already is entry 
	//for this user and project
	$is_checked = 0;
	if($contributors_num['num'] < 1)
	//user becomes contributor if there are no contributors before
	{
		$is_checked = 1;
	}
	if(((int)$did_you_claim['num']) == 0)
	{
		$conn->query("INSERT INTO `participations` (`project_id`, `participant_id`, " 
			. "`mark`, `is_checked`) VALUES ('$proj_id', '" . $user_id['user_id'] 
			. "', '100', '$is_checked');");
	}
	else
	{
		echo "Already claimed.";
		exit;
	}
?>


<html>
<head>
	<title>Claimed</title>
	<link href="..\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		Your claim for contribution in project is submited.<br>
		Wait when accepted project contributor approves you and gives you a mark.<br>
		Then you will see yourself in list.<br><br>
		<a class='blue' href='project.php?id=<?php echo $proj_id; ?>'>Return to project</a>
	</div>
</body>
</html>