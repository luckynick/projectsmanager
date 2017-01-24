<?php
	//show simple list of projects
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	$steps = $config['per_page']; //how many projects on one page
	$offset = $steps * (((int)$_GET['page']) - 1); //for requesting projects for current page
	$number = $offset + $steps;
	$result = $conn->query("SELECT  `id`, `title`, `image`, LEFT(`description`, 100) AS `description`, "
		. "`customer`, `cost` FROM `projects` ORDER BY `id` DESC LIMIT $offset,$steps;");
	$proj_num = $conn->query("SELECT COUNT('id') FROM projects;")->fetch_assoc(); //number of all projects
	$proj_num = $proj_num["COUNT('id')"];
	$file = 'list';
?>

<html>
<head>
	<title>List of projects</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
	<ul class='projList'>
		<?php
			while($project = mysqli_fetch_assoc($result))
			{
				?>
				<li>
					<div class="projInList" >
						<a href="\manager\project.php?id=<?php echo $project['id'] ?>">
						<img src="\res\<?php echo $project['image'] ?>" 
						alt="Preview" height="120">
						</a>
						<div>
						Project: <a class='blue' href="\manager\project.php?id=
						<?php echo $project['id'] . '">' . $project['title'] ?> </a><br>
						<?php if(!empty($project['customer'])) { 
							echo 'Customer: ' . $project['customer']; 
						} else {
							echo 'Customer is not specified.';
						} ?>
						<br>
						<?php if(!empty($project['description'])) { 
							echo 'Description: ' . $project['description'] . '...'; 
						} else {
							echo 'No description.';
						} ?>
						<br>
						<?php if(!empty($project['cost'])) { 
							echo 'Cost: ' . $project['cost'] . '$'; 
						} else {
							echo 'Cost is not specified.';
						} ?>
						<br>
						</div>
					</div>
				</li>
				<?php
			}
		?>
		<div style="clear: both;"></div>
	</ul>
	<?php 
		echo "<span style='margin:auto; display:table;'>Page " . $_GET['page'] . " of "
			. (int)ceil($proj_num/$steps) . "</span><br>";
		if($offset > 0)
		{
			echo "<a style='float:left' href='\manager\\$file.php?page=" 
				. ($_GET['page'] - 1) . "'>Previous page</a>  ";
		}
		if(($number - $proj_num) < 0)
		{
			echo "<a style='float:right' href='\manager\\$file.php?page=" 
				. ($_GET['page'] + 1) . "'>Next page</a>";
		}
	?>
	<br><br>
	</div>
</body>
</html>