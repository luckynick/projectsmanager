<?php
	require_once('..\include\admin_permission.php');
	require_once('..\include\base.php');
	$selection = $conn->query($_SESSION['query']);
	//unset($_SESSION['query']);
?>
<html>
<head>
	<title>List of projects</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
	<h1>Select projects for forming statistics:</h1>
	<form action='statistics.php' method='POST'>
	<ul class='projList'>
		<?php
			$c = 0;
			while($project = mysqli_fetch_assoc($selection))
			{
				$c += 1;
				?>
				<li>
					<input type='checkbox' name='<?php echo $c; ?>' value='<?php echo $project['id']; ?>'>
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
						<br>Publication date:
						<?php echo $project['pub_date']; ?>
						<br>
						</div>
					</div>
				</li>
				<?php
			}
		?>
		<div style="clear: both;"></div>
	</ul>
	<input type='hidden' name='num' value='<?php echo $c; ?>'>
	<button type='submit'>Make statistics for selected projects</button>
	</form>
	</div>
</body>
</html>