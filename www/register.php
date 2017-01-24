<?php
	require_once('include\base.php');
	$execute = true;
	$errors = array();
	$success = false;
	if(!isset($_POST['username']))//don't do
	//anything on first time visit of this page
	{
		$execute = false;
	}
	if(empty($_POST['username']) and isset($_POST['username']))
	//handle empty username field
	{
		$execute = false;
		$errors[] = "Username field is empty.";
	}
	if($execute)
	{
		$is_admin = 0;
		$users_num = $conn->query("SELECT COUNT('user_id') AS 'num' FROM `users`;")->fetch_assoc();
		if(!$users_num['num']) $is_admin = 1;
		$pass_for_eval = md5($_POST['password'] . $salt);
		if($result = $conn->query("SELECT username FROM Users WHERE username='" 
			. $_POST['username'] . "';")->fetch_assoc())
		//check if username doesn't already exist
		{
			if($result['username'] == $_POST['username']) 
				$errors[] = "Username exists.";
		}
		if(empty($_POST['password'])) $errors[] = "Password field is empty.";
		if(empty($_POST['email'])) $errors[] = "Email field is empty.";
		if(empty($_POST['first_name'])) $errors[] = "Name field is empty.";
		if(empty($_POST['last_name'])) $errors[] = "Password field is empty.";
		if(empty($_POST['password2'])) $errors[] = "Repeat password!";
		if($_POST['password2'] != $_POST['password']) 
			$errors[] = "Passwords are not equal.";
		if(empty($errors)) //register new user
		{
			$img_path = 'DEFAULT';
			$query = "INSERT INTO Users (`username`, `password`, `email`, " 
				. "`first_name`, `last_name`, `registration_date`, "
				. "`image`, `description`, `is_admin`) VALUES ('" 
				. $_POST['username'] . "', '$pass_for_eval', '" 
				. $_POST['email'] . "', '" . $_POST['first_name'] . "', '" 
				. $_POST['last_name'] . "', NOW(), DEFAULT, '" 
				. $_POST['description'] . "', '$is_admin');";
			if($conn->query($query))
			{
				$success = true;
			}
			else $errors[] = "Not registered.";
			if($success) //add avatar
			{
				if(!empty($_FILES['image_file']['tmp_name'])) //user might not add own avatar
				{
					$id = $conn->query("SELECT `user_id` FROM `users` WHERE `username`='" 
						. $_POST['username'] . "';")->fetch_assoc();
					$id = $id['user_id'];
					$path = _HOME . "\\res\user_" . $id;
					if(!file_exists($path))
					{
						mkdir($path, 0755, true);
					}
					move_uploaded_file($_FILES["image_file"]['tmp_name'], 
						$path . "\avatar.jpg");
					$img_path = "\user_" . $id . "\avatar.jpg";
					$conn->query("UPDATE `users` SET `image`='" . addslashes($img_path) 
						. "' WHERE `user_id`=$id;");
				}
			}
		}
	}
?>

<html>
<head>
	<title>Registration</title>
	<link href="include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('include\topbar.php'); ?>
	<div style='left: 40%; top:30%;' class='form registration'>
		You are creating a new account<br><br>
		<form action="/register.php" method="POST" enctype="multipart/form-data">
			Username: <input name="username" type="text" 
				value="<?php echo isset($_POST['username'])? $_POST['username'] : ""; ?>">  * <br><br>
			Email: <input name="email" type="email" 
				value="<?php echo isset($_POST['email'])? $_POST['email'] : ""; ?>">  * <br><br>
			First name: <input name="first_name" type="text" 
				value="<?php echo isset($_POST['first_name'])? $_POST['first_name'] : ""; ?>">  * <br><br>
			Last name: <input name="last_name" type="text" 
				value="<?php echo isset($_POST['last_name'])? $_POST['last_name'] : ""; ?>">  * <br><br>
			Password: <input name="password" type="password"> * <br><br>
			Password again: <input name="password2" type="password"> * <br><br>
			<input type="hidden" name="MAX_FILE_SIZE" value="5242880" />
			Upload image for your avatar (max 5MB): 
				<input name="image_file" type="file" /><br><br>
			Your description: <textarea class="description_form" name="description"
				><?php echo isset($_POST['description'])? $_POST['description'] : ""; ?></textarea> <br><br>
			
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