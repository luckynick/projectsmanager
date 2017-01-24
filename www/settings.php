<?php
	require_once("include\permission.php");
	require_once('include\base.php');
	$errors = array(); //here information about failure will
	//be stored
	$success = false;
	$current_values = $conn->query("SELECT * FROM `users` WHERE `username`='" 
		. $_SESSION['logged_user'] . "'")->fetch_assoc();
	$parameters = "";
	$change_done = false;
	//Below is a check if fields in form are not empty and contain special value.
	//If condition is true, value will be addet to query and column will be changed
	//in database.
	if(!empty($_POST['username']) and $_POST['username'] != $current_values['username'])
	{
		$change_done = true;
		$parameters .= "`username`='" . addslashes($_POST['username']) . "'* ";
	}
	if(!empty($_POST['first_name']) and $_POST['first_name'] != $current_values['first_name'])
	{
		$change_done = true;
		$parameters .= "*`first_name`='" . addslashes($_POST['first_name']) . "'* ";
	}
	if(!empty($_POST['last_name']) and $_POST['last_name'] != $current_values['last_name'])
	{
		$change_done = true;
		$parameters .= "*`last_name`='" . addslashes($_POST['last_name']) . "'* ";
	}
	if(!empty($_POST['description']) and $_POST['description'] != $current_values['description'])
	{
		$change_done = true;
		$parameters .= "*`description`='" . addslashes($_POST['description']) . "'* ";
	}
	if(!empty($_POST['password2']) and $_POST['password2'] != $current_values['password2'])
	{
		$change_done = true;
		$parameters .= "*`password`='" . md5(addslashes($_POST['password2'] . $salt)) . "'* ";
	}
	if(!empty($_POST['email']) and $_POST['email'] != $current_values['email'])
	{
		$change_done = true;
		$parameters .= "*`email`='" . addslashes($_POST['email']) . "'";
	}
	if(!empty($_FILES['image_file']['tmp_name'])) $change_done = true;
	
	if(empty($_POST['password'])) $errors[] = "Password field is empty.";
	else if($_POST['password2'] == $_POST['password']) 
		$errors[] = "New and current passwords are the same.";
	else if(md5($_POST['password'] . $salt) != $current_values['password']) 
		$errors[] = "Current password is incorrect.";
	if(empty($errors) and $change_done) //make changes to database
	{
		//add commas between parameters
		$parameters = str_replace("*", "", str_replace("* *", ", ", $parameters));
		if(!empty($parameters)) //update basic info
		{
			$conn->query("UPDATE `users` SET $parameters WHERE `user_id`='" 
				. $current_values['user_id'] . "';");
		}
		if(!empty($_FILES['image_file']['tmp_name'])) //update avatar
		{
			$id = $current_values['user_id'];
			$extension = get_extension($_FILES["image_file"]['type']);
			if(!empty($extension))
			{
				$path = _HOME . "\\res\user_" . $id;
				if(!file_exists($path))
				{
					mkdir($path, 0755, true);
				}
				move_uploaded_file($_FILES["image_file"]['tmp_name'], $path . "\avatar.jpg");
				$img_path = "\user_" . $id . "\avatar.jpg";
				$conn->query("UPDATE `users` SET `image`='" 
					. addslashes($img_path) . "' WHERE `user_id`=$id;");
			}
			else $errors[] = "Image format is wrong.";
		}
		$success = true;
		$current_values = $conn->query("SELECT * FROM `users` WHERE `username`='" 
			. $_SESSION['logged_user'] . "'")->fetch_assoc(); //get new information
	}
	else
	{
		$errors[] = "Youd didn't provide new settings.";
	}
?>

<html>
<head>
	<title>Profile settings</title>
	<link href="include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
		<h1>Profile settings</h1><br><br>
		<form action="/settings.php" method="POST" enctype="multipart/form-data">
			Username: <input name="username" type="text" 
				value="<?php echo isset($_POST['username'])? $_POST['username'] : ""; ?>"> 
				(now: <?php echo $current_values['username']; ?>) <br><br>
			Current password: <input name="password" type="password"> Required field <br><br>
			New password: <input name="password2" type="password"> <br><br>
			Email: <input name="email" type="email" value="<?php echo isset($_POST['email'])? $_POST['email'] : ""; ?>"> 
				(now: <?php echo $current_values['email']; ?>) <br><br>
			First name: <input name="first_name" type="text" 
				value="<?php echo isset($_POST['first_name'])? $_POST['first_name'] : ""; ?>"> 
				(now: <?php echo $current_values['first_name']; ?>) <br><br>
			Last name: <input name="last_name" type="text" 
				value="<?php echo isset($_POST['last_name'])? $_POST['last_name'] : ""; ?>"> 
				(now: <?php echo $current_values['last_name']; ?>) <br><br>
			<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
			Upload image for your avatar (max 5MB): <input name="image_file" type="file" /><br><br>
			Your description: <textarea class="description_form" name="description"
				><?php echo isset($_POST['description'])? $_POST['description'] : ""; ?></textarea> <br><br>
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