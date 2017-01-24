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
	<form action='stat_select.php' method='POST'>
		<input type='hidden' name='stat' value='stat'>
		<button type='submit'>Get statistics for this projects</button>
	</form>
	</div>
</body>
</html>