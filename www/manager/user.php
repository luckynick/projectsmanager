<?php
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	$user_id = (int)$_GET['id'];
	//get profile data
	$user_data = $conn->query("SELECT `username`, `email`, " 
		. "`first_name`, `last_name`, `registration_date`, "
		. "`image`, `description`, `keywords`, `is_admin` FROM `users` "
		. "WHERE `user_id`='$user_id';")->fetch_assoc();
	//get projects where contributed (only approved contribution)
	$projects_nonassoc = $conn->query("SELECT projects.`id`, " 
		. "projects.`title`, projects.`pub_date`, "
		. "LEFT(projects.`description`, 100) AS `description`, " 
		. "projects.`image`, participations.`part_id` FROM `projects` "
		. "INNER JOIN `participations` ON projects.`id`=participations.`project_id` "
		. "WHERE participations.`participant_id`='$user_id' " 
		. "AND participations.`is_checked`='1';");
	$are_you_admin = $conn->query("SELECT `is_admin` FROM `users` " 
		. "WHERE `username`='" . $_SESSION['logged_user'] . "';")->fetch_assoc();
	$are_you_admin = (int)$are_you_admin['is_admin'];
?>

<html>
<head>
	<title><?php echo "User: " . $user_data['username']; ?></title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		<h1 align='center'><?php echo 'User: ' . $user_data['username']; 
			if($user_data['is_admin']){ echo " (ADMIN)"; }?></h1><br>
		<?php if($are_you_admin){ if(!$user_data['is_admin']){ ?>
				<a href='newadmin.php?id=<?php echo $_GET['id'] ?>' class='blue' 
					style='float: right'>Make this user an admin</a>
		<?php } ?>
			<br><a href='rmuser.php?id=<?php echo $_GET['id'] ?>' class='blue' 
			style='float: right'>Remove user</a>
		<?php } ?>
		<img src="\res\<?php echo $user_data['image'] ?>" 
			alt="Title image" height="300" align='top'>
			<b>Name and surname:</b>
			<?php if(!empty($user_data['first_name']) 
						and !empty($user_data['last_name'])) { 
					echo $user_data['first_name'] 
						. " " . $user_data['last_name'];
				} else {
					echo 'not specified.';
			} ?>
			<br><b>Registration date:</b>
			<?php echo $user_data['registration_date']; ?>
			<br><b>Keywords:</b>
			<?php if(!empty($user_data['keywords'])) { 
					echo $user_data['keywords']; 
				} else {
					echo 'not specified.';
			} ?>
			<br><b>E-mail:</b>
			<?php if($are_you_admin and !empty($user_data['email'])) { 
					echo $user_data['email']; 
				} else if(!$are_you_admin and !empty($user_data['email'])){
					echo 'secret info.';
				}else {
					echo 'not specified.';
			} ?>
			<br><b>Description: </b>
			<?php if(!empty($user_data['description'])) { 
					echo $user_data['description']; 
				} else {
					echo 'not specified.';
			} ?> 
			
			
			<br><br><b>Took part in projects:</b>
			<?php 
				if($projects_nonassoc->num_rows > 0)
				{
					echo '<br><ul>';
					while($project = $projects_nonassoc->fetch_assoc())
					{
						?>
						<li>
							<div class='contributor'>
								<a href="<?php echo "\manager\project.php?id=" 
									. $project['id'];?>">
									<img src="\res<?php 
										echo $project['image'] ?>" height="50">
								</a>
								<div style='float: left;'>
									Project: <a class='blue' href="<?php 
										echo "\manager\project.php?id=" 
											. $project['id'];?>">
										<?php echo $project['title']; ?></a><br>
									Date of publication: 
										<?php echo $project['pub_date']; ?>
								</div>
								<div style='margin-left: 30px; float: 
									left; word-wrap: break-word; width: 35%;'>
									Description: 
									<?php if(!empty($project['description'])) { 
											echo $project['description'] . "..."; 
										} else {
											echo 'not specified.';
									} ?> 
								</div>
								<a style='margin-left: 5px; display: inline-block; float: right;' class='blue' 
									href='rmpart.php?id=<?php echo $project['part_id']; ?>'>
										Remove contribution</a>
							</div>
						</li><br>
						<?php
					}
					echo '</ul>';
				}
				else
				{
					echo ' no projects.';
				}	
			?>
	</div>
</body>
</html>