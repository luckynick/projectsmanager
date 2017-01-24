<?php
	//Scrypt for changing project's parameters.
	require_once('..\include\contributor_permission.php');
	require_once('..\include\base.php');
	$errors = array();//here information about failure will
	//be stored
	$success = false;
	$current_values = $conn->
		query("SELECT * FROM `projects` WHERE `id`='" . $_GET['id'] . "'")->fetch_assoc();
	$parameters = "";
	$change_done = false;
	//Below is a check if fields in form are not empty and contain special value.
	//If condition is true, value will be addet to query and column will be changed
	//in database.
	if(!empty($_POST['title']) and $_POST['title'] != $current_values['title'])
	{
		$change_done = true;
		$parameters .= "`title`='" . addslashes($_POST['title']) . "'* ";
	}
	if(!empty($_POST['customer']) and $_POST['customer'] != $current_values['customer'])
	{
		$change_done = true;
		$parameters .= "*`customer`='" . addslashes($_POST['customer']) . "'* ";
	}
	if(!empty($_POST['cost']) and $_POST['cost'] != $current_values['cost'])
	{
		$change_done = true;
		$parameters .= "*`cost`='" . addslashes($_POST['cost']) . "'* ";
	}
	if(!empty($_POST['description']) and $_POST['description'] != $current_values['description'])
	{
		$change_done = true;
		$parameters .= "*`description`='" . addslashes($_POST['description']) . "'* ";
	}
	if(!empty($_POST['length']) and $_POST['length'] != $current_values['length'])
	{
		$change_done = true;
		$parameters .= "*`length`='" . addslashes($_POST['length']) . "'* ";
	}
	if(!empty($_FILES['image_file']['tmp_name'])) $change_done = true;
	if(!empty($_FILES['text_file']['tmp_name'])) $change_done = true;
	if(!empty($_FILES['declar_file']['tmp_name'])) $change_done = true;
	if(empty($errors) and $change_done) //make changes to database
	{
		//add commas between parameters
		$parameters = str_replace("*", "", str_replace("* *", ", ", $parameters));
		$id = $current_values['id'];
		if(!empty($parameters))//update basic info
		{
			$conn->query("UPDATE `projects` SET $parameters WHERE `id`='$id';");
		}
		$img_path = $current_values['image'];
		$text_path = $current_values['text'];
		$decl_path = $current_values['declaration'];
		$project_id = $_GET['id'];
		$path = _HOME . "\\res\proj_" . $project_id;
		if(!file_exists($path))
		{
			mkdir($path, 0755, true);
		}
		if(!empty($_FILES['image_file']['tmp_name'])) //update image
		{
			$ext = get_extension($_FILES["image_file"]['type']);
			if(!empty($ext))
			{
				move_uploaded_file($_FILES["image_file"]['tmp_name'], $path . "\\image." . $ext);
				$img_path = "\proj_" . $project_id . "\\image." . $ext;
			}
			else $errors[] = "Wrong file extension.";
		}
		if(!empty($_FILES['text_file']['tmp_name'])) //update text file of project
		{
			$ext = get_extension($_FILES["text_file"]['type']);
			if(!empty($ext))
			{
				move_uploaded_file($_FILES["text_file"]['tmp_name'], $path . "\\text." . $ext);
				$text_path = "\proj_" . $project_id . "\\text." . $ext;
			}
			else $errors[] = "Wrong file extension.";
		}
		if(!empty($_FILES['declar_file']['tmp_name'])) //update declaration file
		{
			$ext = get_extension($_FILES["declar_file"]['type']);
			if(!empty($ext))
			{
				move_uploaded_file($_FILES["declar_file"]['tmp_name'], $path . "\\declar." . $ext);
				$decl_path = "\proj_" . $project_id . "\\declar." . $ext;
			}
			else $errors[] = "Wrong file extension.";
		}
		if($img_path != $current_values['image'] or $decl_path != $current_values['declaration']
			or $text_path != $current_values['text'])
		{
			$conn->query("UPDATE `projects` SET `image`='" . addslashes($img_path) 
				. "', `text`='" . addslashes($text_path) . "', `declaration`='" 
				. addslashes($decl_path) . "' WHERE `id`='$project_id';");
		}
		$success = true;
		$current_values = $conn->query("SELECT * FROM `projects` WHERE `id`='$id'")->fetch_assoc();
	}
	else
	{
		$errors[] = "Youd didn't provide new parameters.";
	}
?>

<html>
<head>
	<title>Project parameters changing</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		<h1>Project parameters changing <small><a class='blue' 
			href='\manager\project.php?id=<?php echo $current_values['id']; ?>'>Link</a></small></h1>
		<br><br>
		<form action="\manager\modifydoc.php?id=<?php echo $current_values['id']; ?>" 
				method="POST" enctype="multipart/form-data">
			<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
			Title: <input name="title" type="text" value="<?php echo isset($_POST['title'])? $_POST['title'] : ""; ?>"> 
				(now: <?php echo $current_values['title']; ?>) <br><br>
			Customer: <input name="customer" type="text" value="<?php echo isset($_POST['customer'])? $_POST['customer'] : ""; ?>"> 
				(now: <?php echo $current_values['customer']; ?>) <br><br>
			Cost (in dollars): <input name="cost" type="text" maxlength=20 value="<?php echo isset($_POST['cost'])? $_POST['cost'] : ""; ?>"> 
				(now: <?php echo $current_values['cost']; ?>) <br><br>
			Description of project: <textarea style='display:inline-block;' class="description_form" 
				style="" name="description"><?php echo isset($_POST['description'])? $_POST['description'] : ""; ?></textarea> <br><br>
			Length of project in <?php echo $config['length_unit']; ?>: 
				<input name="length" type="text" maxlength=6 value="<?php echo isset($_POST['length'])? $_POST['length'] : ""; ?>"> 
				(now: <?php echo $current_values['length']; ?>) <br><br>
			Official page: <input name="link" type="text" value="<?php echo isset($_POST['link'])? $_POST['link'] : ""; ?>"> 
				(now: <?php echo $current_values['link']; ?>) <br><br>
			<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
			Upload image for project (jpg, max 5MB): <input name="image_file" type="file" /><br><br>
			Upload file with content of project (max 5MB): <input name="text_file" type="file" /><br><br>
			Upload file with declaration (max 5MB): <input name="declar_file" type="file" /><br><br>
			
			<button type="submit">Make changes</button><br>
		</form>
		<?php if($success) : ?>
		<span class="message success">Successfully changed.</span>
		<?php else : ?>
		<span class="message error"><?php if(!empty($errors)) echo $errors[0] ?></span>
		<?php endif;?>
		<br>
	</div>
</body>
</html>