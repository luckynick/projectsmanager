<?php
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	$project_id = (int)$_GET['id'];
	//get project basic info
	$project_result = $conn->query("SELECT `pub_date`, `title`, `image`, `description`, 
		`declaration`, `text`, `cost`, `link`, `customer`, `length` FROM `projects` 
		WHERE `id`=$project_id;")->fetch_assoc();
	//get list of contributors
	$participants_nonassoc = $conn->query("SELECT users.`user_id`, users.`username`, " 
		. "users.`first_name`, users.`last_name`, users.`image`, participations.`mark`, " 
		. "participations.`is_checked`, participations.`part_id`, participations.`project_id` FROM `users` "
		. "INNER JOIN `participations` ON users.`user_id`=participations.`participant_id` "
		. "WHERE participations.`project_id`=$project_id ORDER BY `mark` DESC;");
	$are_you_admin = $conn->query("SELECT `is_admin` FROM `users` " 
		. "WHERE `username`='" . $_SESSION['logged_user'] . "';")->fetch_assoc();
	$are_you_admin = (int)$are_you_admin['is_admin'];
?>

<html>
<head>
	<title><?php echo $project_result['title'] ?> project</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
	<h1 align='center'><?php echo 'Project: ' . $project_result['title']; ?></h1><br>
	<?php if($are_you_admin){ ?>
		<a href='rmproject.php?id=<?php echo $_GET['id'] ?>' class='blue' 
			style='float: right'>Remove project</a>
	<?php } ?>
	<?php 
		$result = $conn->query("SELECT `is_checked` FROM `participations` INNER JOIN `users` " 
			. "ON participations.`participant_id`=users.`user_id` WHERE `project_id`='" 
			. $_GET['id'] . "' AND `username`='" . $_SESSION['logged_user'] . "';")->fetch_assoc();
		$is_checked = (int)$result['is_checked'];
		if($is_checked == 1)
		{
			echo "<a style='float: right' class='blue' href=modifydoc.php?id=" 
				. $project_id . ">Modify project parameters</a>";
		}
	?>
	<p></p><img src="\res\<?php echo $project_result['image'] ?>" 
		alt="Title image" height="300" align='top'>
		<b>Customer:</b>
		<?php if($are_you_admin and !empty($project_result['customer'])) { 
					echo $project_result['customer']; 
				} else if(!$are_you_admin and !empty($project_result['customer'])){
					echo 'secret info.';
				}else {
					echo 'not specified.';
			} ?>
		<br><b>Published:</b>
		<?php echo $project_result['pub_date']; ?>
		<br><b>Cost:</b>
		<?php if($are_you_admin and !empty($project_result['cost'])) { 
					echo $project_result['cost'] . '$'; 
				} else if(!$are_you_admin and !empty($project_result['cost'])){
					echo 'secret info.';
				}else {
					echo 'not specified.';
			} ?>
		<br><b>Length in <?php echo $config['length_unit']; ?>:</b>
		<?php if(!empty($project_result['length'])) { 
				echo $project_result['length']; 
			} else {
				echo 'not specified.';
		} ?>
		<br><b>
		<?php if(!empty($project_result['declaration'])) { 
				echo "<a class='blue' href='" . $project_result['link'] 
					. "'>Project's official page</a>"; 
			} else {
				echo "No project's official page.";
		} ?> </b>
		<br><b>Description: </b>
		<?php if(!empty($project_result['description'])) { 
				echo $project_result['description']; 
			} else {
				echo 'not specified.';
		} ?> 
		<br><br><b>Downloads:</b>
		<?php 
			if(isset($project_result['declaration']) or isset($project_result['text']))
			{
				echo "<br><ul class='downloads'>";
				if(!empty($project_result['text'])) {
					$path = project_res_path($project_result['text']);
					echo "<li><a class='blue' href='$path"
						. "' download>Text of project</a> [" . (int)(filesize(_HOME . $path)/1024)
						. " kbytes]</li>"; 
				}
				if(!empty($project_result['declaration'])) { 
					$path = project_res_path($project_result['declaration']);
					echo "<li><a class='blue' href='$path"
						. "' download>Declaration</a> [" . (int)(filesize(_HOME . $path)/1024)
						. " kbytes]</li>"; 
				}
				echo "</ul><br><br>";
			}
			else
			{
				echo " no files.";
			}
		?>
	</p><b>Contributors:</b>
	<?php 
		$you_contributor = false;
		$in_list = false;
		$candidates = array();
		if($participants_nonassoc->num_rows > 0)
		{
			echo '<br><ul>';
			while($mate = $participants_nonassoc->fetch_assoc())
			{
				if($mate['username'] == $_SESSION['logged_user'] and $mate['is_checked'] == 1)
				{
					$you_contributor = true;
				}
				if($mate['username'] == $_SESSION['logged_user'])
				{
					$in_list = true;
				}
				if($mate['is_checked'] == 0)
				{
					$candidates[] = $mate;
					continue;
				}
				?>
				<li>
					<div class='contributor'>
						<a href="<?php echo "\manager\user.php?id=" . $mate['user_id'];?>">
							<img src="\res<?php echo $mate['image'] ?>" height="50">
						</a>
						<div>
							Username: <a class='blue' href="
								<?php echo "\manager\user.php?id=" . $mate['user_id'];?>">
								<?php echo $mate['username']; ?></a><br>
						<?php if($mate['username'] == $_SESSION['logged_user']) { ?>
							<a style='margin-left: 5px; float: right;' class='blue' 
								href='rmpart.php?id=<?php echo $mate['part_id']; ?>'>
								Remove contribution</a>
						<?php } ?> 
							Name and surname: <?php echo $mate['first_name'] . ' ' 
								. $mate['last_name']; ?><br>
							Level of contribution: <?php echo $mate['mark'] . "/" 
								. $config['mark_limit']; ?>
						</div>
					</div>
				</li><br>
				<?php
			}
			echo '</ul>';
			if(count($candidates) > 0 and $you_contributor) 
				//only contributors can approve new contributors for project
			{
				echo "<br><br><b>Candidates for being contributor:</b><br><ul>";
				$mark_field_length = (int)ceil(log10((float)$config['mark_limit'])) + 1;
				foreach($candidates as $candidate)
				{
				?>
				<li>
					<div class='contributor'>
						<a href="<?php echo "\manager\user.php?id=" . $candidate['user_id'];?>">
							<img src="\res<?php echo $candidate['image'] ?>" height="50">
						</a>
						<div>
							Username: <a class='blue' href="<?php echo "\manager\user.php?id=" 
								. $candidate['user_id'];?>">
								<?php echo $candidate['username']; ?></a><br>
							<?php if($mate['username'] == $_SESSION['logged_user']) { ?>
								<a style='margin-left:5px; float: right;' class='blue' 
									href='rmpart.php?id=<?php echo $mate['part_id']; ?>
									'>Remove contribution</a>
							<?php } ?> 
							Name and surname: <?php echo $candidate['first_name'] . ' ' 
								. $candidate['last_name']; ?><br>
							<form style='display: inline;' action='newcontributor.php' method='POST'>
								<input type='hidden' name='part_id' value=
									'<?php echo $candidate['part_id']; ?>'>
								<input type='hidden' name='project_id' value=
									'<?php echo $candidate['project_id']; ?>'>
								Level of contribution: <input name='mark' type='text' maxlength='
									<?php echo $mark_field_length ?>' size='
									<?php echo $mark_field_length ?>'>/
									<?php echo $config['mark_limit']; ?>
								<button type="submit">Approve</button>
							</form>
							<form style='display: inline;' action='newcontributor.php' method='POST'>
								<input type='hidden' name='part_id' value=
									'<?php echo $candidate['part_id']; ?>'>
								<input type='hidden' name='project_id' value=
									'<?php echo $candidate['project_id']; ?>'>
								<input type='hidden' name='mark' value=-1>
								<button type="submit">Decline</button>
							</form>
						</div>
					</div>
				</li><br>
				<?php
				}
			}
		}
		else
		{
			echo ' no contributors.';
		}	
		if(!$you_contributor and !$in_list)
		{
			?>
				<br><br>
				<a class='blue' href='claim.php?project_id=<?php echo $project_id; ?>&
					user=<?php echo $_SESSION['logged_user']; ?>'>Claim your contribution</a><br>
			<?php
		}
	?>
	</div>
</body>
</html>