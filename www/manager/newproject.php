<?php
	require_once('..\include\base.php');
	require_once('..\include\permission.php');
	$execute = true;
	$errors = array();
	$success = false;
	if(!isset($_POST['title'])) //don't do
	//anything on first time visit of this page
	{
		$execute = false;
	}
	if(empty($_POST['title']) and isset($_POST['title']))
	//handle empty title field
	{
		$execute = false;
		$errors[] = "Title field is empty.";
	}
	if($execute)
	{
		if($result = $conn->query("SELECT `title` FROM `projects` WHERE `title`='" 
			. addslashes($_POST['title']) . "';")->fetch_assoc())
		{
			//check if title doesn't already exist
			if($result['title'] == $_POST['title']) $errors[] = "Title exists.";
		}
		if(empty($_POST['customer'])) $errors[] = "Customer field is empty.";
		if(empty($_POST['cost'])) $errors[] = "Cost field is empty.";
		if(empty($_POST['description'])) $errors[] = "Description field is empty.";
		if(empty($_POST['mark'])) $errors[] = "Level of contribution field is empty.";
		if(empty($errors)) //add new project
		{
			$query = "INSERT INTO `projects` (`pub_date`, `title`, `image`," 
				. " `declaration`, `text`, `cost`, `link`, `customer`, `description`," 
				. " `length`) VALUES (NOW(), '" . addslashes($_POST['title']) 
				. "', DEFAULT, DEFAULT, DEFAULT, '" . addslashes($_POST['cost']) . "' ,'"
				. addslashes($_POST['link']) . "', '" . addslashes($_POST['customer']) 
				. "', '" . addslashes($_POST['description']) . "', '" 
				. addslashes($_POST['length']) . "');";
			if($conn->query($query))
			{
				$success = true;
			}
			else $errors[] = "Not created.";
			if($success)
			{
				$first_user_id = $conn->query("SELECT `user_id` FROM `users` WHERE `username`='" 
					. $_SESSION['logged_user'] . "';")->fetch_assoc();
				$first_user_id = $first_user_id['user_id'];
				$project_id = $conn->query("SELECT `id` FROM `projects` WHERE `title`='" 
					. $_POST['title'] . "';")->fetch_assoc();
				$project_id = $project_id['id'];
				$conn->query("INSERT INTO `participations` (`participant_id`, `project_id`, " 
					. "`mark`, `is_checked`) VALUES ('$first_user_id', '$project_id', '"
					. addslashes($_POST['mark']) . "', '1');"); 
					//author of project becomes contributor automaticaly
				$img_path = "DEFAULT"; //if author doesn't upload own files, 
				//default values vill be assigned to project's fields in database
				$decl_path = "DEFAULT";
				$text_path = "DEFAULT";
				$path = _HOME . "\\res\proj_" . $project_id;
				if(!file_exists($path))
				{
					mkdir($path, 0755, true);
				}
				if(!empty($_FILES['image_file']['tmp_name'])) //add image
				{
					$ext = get_extension($_FILES["image_file"]['type']);
					move_uploaded_file($_FILES["image_file"]['tmp_name'], $path . "\image." . $ext);
					$img_path = "'" . addslashes("\proj_" . $project_id . "\image." . $ext) . "'";
				}
				if(!empty($_FILES['text_file']['tmp_name'])) //add full text description of project
				{
					$ext = get_extension($_FILES["text_file"]['type']);
					move_uploaded_file($_FILES["text_file"]['tmp_name'], $path . "\\text." . $ext);
					$text_path = "'" . addslashes("\proj_" . $project_id . "\\text." . $ext) . "'";
				}
				if(!empty($_FILES['declar_file']['tmp_name'])) //add declaration
				{
					$ext = get_extension($_FILES["declar_file"]['type']);
					move_uploaded_file($_FILES["declar_file"]['tmp_name'], $path . "\\declar." . $ext);
					$decl_path = "'" . addslashes("\proj_" . $project_id . "\declar." . $ext) . "'";
				}
				if($img_path != "DEFAULT" or $decl_path != "DEFAULT" or $text_path != "DEFAULT")
				//update project's row in database if at least one of files was provided by user
				{
					$conn->query("UPDATE `projects` SET `image`=" . $img_path 
						. ", `text`=" . $text_path . ", `declaration`=" 
						. $decl_path . " WHERE `id`='$project_id';");
				}
			}
		}
	}
?>

<html>
<head>
	<title>Create project</title>
	<link href="..\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php 
		require_once('..\include\topbar.php'); 
		$mark_field_length = (int)ceil(log10((float)$config['mark_limit'])) + 1;
	?>
	<div style='left: 40%; top:30%;' class='form registration'>
		You are creating a new project<br><br>
		<form style="align-items: top;" action="newproject.php" method="POST" 
				enctype="multipart/form-data">
			Title: <input name="title" type="text" value="<?php echo isset($_POST['title'])? $_POST['title'] : ""; ?>"> 
				* <br><br>
			Customer: <input name="customer" type="text" value="<?php echo isset($_POST['customer'])? $_POST['customer'] : ""; ?>"> 
				* <br><br>
			Cost (in dollars): <input name="cost" type="text" maxlength=20 
				value="<?php echo isset($_POST['cost'])? $_POST['cost'] : ""; ?>">  * <br><br>
			Description of project: <textarea style='display:inline-block;' class="description_form" 
				style="" name="description"><?php echo isset($_POST['description'])? $_POST['description'] : ""; ?></textarea> * <br><br>
			Level of your contribution: <input name='mark' type='text' maxlength='
				<?php echo $mark_field_length ?>' size='
				<?php echo $mark_field_length ?>' value=
				'<?php echo isset($_POST['mark'])? $_POST['mark'] : "" ?>'>/
				<?php echo $config['mark_limit']; ?> * <br><br>
			Length of project in <?php echo $config['length_unit']; ?>: 
				<input name="length" type="text" maxlength=6 
				value="<?php echo isset($_POST['length'])? $_POST['length'] : ""; ?>"> <br><br>
			Official page: <input name="link" type="text" 
				value="<?php echo isset($_POST['link'])? $_POST['link'] : ""; ?>"> <br><br>
			<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
			Upload image for project (jpg, max 5MB): 
				<input name="image_file" type="file" /><br><br>
			Upload file with content of project (max 5MB): 
				<input name="text_file" type="file" /><br><br>
			Upload file with declaration (max 5MB): 
				<input name="declar_file" type="file" /><br><br>
			
			<button type="submit">Register</button><br>
		</form>
		<?php if($success) : ?>
		<span class="message success">Successfully registered.</span>
		<?php else : ?>
		<span class="message error"><?php if(!empty($errors)) echo $errors[0] ?></span>
		<?php endif;?>
		<br>* - indicator of obligatory field
	</div>
</body>
</html>