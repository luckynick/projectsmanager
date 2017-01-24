<?php
	//list of keywords is refreshed every time user comes on this page
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	$zero_date = $conn->query("SELECT `registration_date` FROM `users` " 
		. "WHERE `user_id`=(SELECT MIN(`user_id`) FROM `users`);")->fetch_assoc();
	$user_data = $conn->query("SELECT `registration_date`, `user_id`, " 
		. "`keywords` FROM `users` WHERE `username`='" 
		. $_SESSION['logged_user'] . "';")->fetch_assoc();
	$particips_nonf = $conn->query("SELECT `project_id`, `participant_id`, " 
		. "`mark`, `is_checked` FROM `participations` WHERE `participant_id`=(SELECT `user_id` FROM `users` WHERE `username`='" 
		. $_SESSION['logged_user'] . "');");
	$num_of_parts = mysqli_num_rows($particips_nonf);
	$project_ids = "";
	$mid_mark = 0;
	$unchecked = 0;
	while($result = $particips_nonf->fetch_assoc())
	{
		$mid_mark += $result['mark'];
		if(!$result['is_checked']) $unchecked += 1;
		else $project_ids .= "*'" . $result['project_id'] . "'* ";
	}
	if($num_of_parts)
	{
		$mid_mark /= (int)($num_of_parts - $unchecked);
	}
	$project_ids = str_replace("*", "", str_replace("* *", ", ", $project_ids));
	if(!empty($project_ids))
	{
	$num_of_mates = $conn->query("SELECT COUNT(`part_id`) AS `num_of_mates` " 
		. "FROM `participations` WHERE `project_id` IN ($project_ids) AND `is_checked` = '1';")
		->fetch_assoc();
	$zero_timestamp = strtotime($zero_date['registration_date']);
	$user_timestamp = strtotime($user_data['registration_date']);
	$keywords = $user_data['keywords'];
	if(time() - $user_timestamp > $config['keywords']['grandpa']*60*60*24) 
		//user is old
	{
		if(strpos($keywords, "grandpa") == false)
		{
			$keywords .= "'grandpa' ";
		}
	}
	else
	{
		$keywords = str_replace("'grandpa' ", "", $keywords);
	}
	if($num_of_parts - $unchecked > $config['keywords']['hardworking']) 
		//user has a lot of contributions
	{
		if(strpos($keywords, "hardworking") == false)
		{
			$keywords .= "'hardworking' ";
		}
	}
	else
	{
		$keywords = str_replace("'hardworking' ", "", $keywords);
	}
	if($mid_mark > $config['keywords']['reliable']) 
		//user has a lot of contributions
	{
		if(strpos($keywords, "reliable") == false)
		{
			$keywords .= "'reliable' ";
		}
	}
	else
	{
		$keywords = str_replace("'reliable' ", "", $keywords);
	}
	if($num_of_mates['num_of_mates'] > $config['keywords']['num_of_mates_high'] * ($num_of_parts - $unchecked))
	{
		if(strpos($keywords, "team worker") == false)
		{
			$keywords .= "'team worker' ";
		}
	}
	else
	{
		$keywords = str_replace("'team worker' ", "", $keywords);
	}
	if($num_of_mates['num_of_mates'] < $config['keywords']['num_of_mates_low'] * ($num_of_parts - $unchecked))
	{
		if(strpos($keywords, "individualist") == false)
		{
			$keywords .= "'individualist' ";
		}
	}
	else
	{
		$keywords = str_replace("'individualist' ", "", $keywords);
	}
	if($user_data['keywords'] !== $keywords)
	{
		$conn->query("UPDATE `users` SET `keywords`='" . addslashes($keywords) . "' WHERE `user_id`=" . $user_data['user_id'] . ";");
	}
	}
?>

<html>
<head>
	<title>Manager</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php') ?> 
	<div class='protector_of_the_realm'>
	<h1 align='center'> Welcome, <?php echo $_SESSION['logged_user']; ?>! </h1>
	</div>
</body>
</html>