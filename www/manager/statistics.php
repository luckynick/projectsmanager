<?php
	require_once('..\include\admin_permission.php');
	require_once('..\include\base.php');
	//unset($_SESSION['query']);
	$ids = "";
	for($i = 0; $i <= (int)$_POST['num']; $i++)
	{
		if(!empty($_POST["$i"]))
		{
			$ids .= "*'" . $_POST["$i"] . "'*";
		}
	}
	$ids = str_replace("*", "", str_replace("**", ", ", $ids));
	$projects_nonf = $conn->query("SELECT * FROM `projects` WHERE `id` IN ($ids);");
	$mid_length = 0;
	$mid_cost = 0;
	$max_length = 0;
	$max_cost = 0;
	$with_decl = 0;
	$with_text = 0;
	$min_date = PHP_INT_MAX;
	$ind_section = "";
	$projects = array();
	while($project = $projects_nonf->fetch_assoc())
	{
		$projects[] = $project;
		$parts = $conn->query("SELECT * FROM `participations` WHERE `project_id`='" . $project['id'] . "' AND `is_checked`='1';");
		$mid_mark = 0;
		$num_parts = mysqli_num_rows($parts);
		while($part = $parts->fetch_assoc())
		{
			$mid_mark += $part['mark'];
		}
		if($num_parts) $mid_mark /= $num_parts;
		else $mid_mark = 0;
		$ind_section .= "<div style='border: 2px solid black; margin: 10px 0 10px 0;'><b>Statistics for '" 
			. $project['title'] . "' project:</b><br>" 
			. "Contributions: $num_parts<br>Middle mark for all contributors: $mid_mark</div>"; 
		if($max_length < $project['length'])
		{
			$max_length = $project['length'];
			$max_length_hint = "<a href='project.php?id=" . $project['id'] . "'>" . $project['title'] . "</a>";
		}
		if($max_cost < $project['cost'])
		{
			$max_cost = $project['cost'];
			$max_cost_hint = "<a href='project.php?id=" . $project['id'] . "'>" . $project['title'] . "</a>";
		}
		$ts = strtotime($project['pub_date']);
		if($min_date > $ts)
		{
			$min_date = $ts;
			$min_date_hint = "<a href='project.php?id=" . $project['id'] . "'>" . $project['title'] . "</a>";
		}
		if($project['text']) $with_text++;
		if($project['declaration']) $with_decl++;
		$mid_length += $project['length'];
		$mid_cost += $project['cost'];
	}
	$proj_num = count($projects);
	$mid_length /= $proj_num;
	$mid_cost /= $proj_num;
?>
<html>
<head>
	<title>Statistics</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
	<style type="text/css"> .protector_of_the_realm a{color: blue;}</style>
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		Statistics for projects: 
		<?php 
			foreach($projects as $one)
			{
				echo "<br><a href='project.php?id=" . $one['id'] . "'>" . $one['title'] . "</a>";
			}
		?><br><br>
		<b>Number of projects: </b><?php echo $proj_num; ?><br>
		<b>Middle cost of projects: </b><?php echo $mid_cost; ?><br>
		<b>Project with biggest cost: </b><?php echo $max_cost . " ($max_cost_hint)"; ?><br>
		<b>Middle length of projects in </b><?php echo $config['length_unit'] . ": " . $mid_length; ?><br>
		<b>Project with biggest length in </b><?php echo $config['length_unit'] . ": " . $max_length . " ($max_length_hint)"; ?><br>
		<b>Oldest project: </b><?php echo date('m/d/Y H:i', $min_date) . " ($min_date_hint)"; ?><br>
		<b>Number of projects with text file: </b><?php echo $with_text; ?><br>
		<b>Number of projects with declaration file: </b><?php echo $with_decl; ?><br>
		
		<br><?php echo $ind_section; ?>
	</div>
</body>
</html>