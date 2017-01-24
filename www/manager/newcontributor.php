<?php 
	//scrypt for accepting user to list of contributors.
	//User has to declare will to be contributor before by
	//clicking appropriate link on page of project.
	//Then any approved contributor can accept this user.
	require_once('..\include\base.php');
	require_once('..\include\common.php');
	require_once('..\include\contributor_permission.php');
	$empty = false;
	if(empty($_POST['mark'])) //contribution has to be evaluated
	{
		echo "You didn't provide mark.";
	}
	if(!$empty)
	{
		$mark = (int)$_POST['mark'];
		if($mark == -1)
		{
			$conn->query("DELETE FROM `participations` WHERE `part_id`='" 
				. $_POST['part_id'] . "';");
			echo "Contribution was declined.";
		}
		else if($mark > $config['mark_limit'] or $mark < 0)
		{
			echo "Mark is not in accepted boundaries.";
		}
		else
		{
			$conn->query("UPDATE `participations` SET `is_checked`='1', `mark`='$mark' " 
				. "WHERE `part_id`='" . $_POST['part_id'] . "';");
			echo "Contribution was accepted.";
		}
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