<?php
	require('include\base.php');
	if(!empty($_POST['username']))
	{
		$pass_for_eval = md5($_POST['password'] . $salt);
		$sql = 'SELECT username, password FROM Users WHERE username = "' 
			. $_POST['username'] . '"'; //get row with provided username
		$result = $conn->query($sql)->fetch_assoc();
		$errors = array();
		if(isset($result['username']))
		{
			if($pass_for_eval == $result['password'])
			//check if password is correct
			{
				$_SESSION['logged_user'] = $_POST['username'];
				header("Location: \\");
				exit;
			}
			else 
			{
				$errors[] = "Password is wrong";
			}
		}
		else
		{
			$errors[] = "No such user name";
		}
	echo $errors[0];
	}
	$conn->close();
?>

<html>
	<head>
		<title>Authorization</title>
		<link href="include\style.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
		<?php require_once('include\topbar.php'); ?>
		<div class='form login'>
			Authorization<br><br>
			<form action="/login.php" method="POST">
				username: <input type="text" name="username"><br><br>
				password: <input type="password" name="password"><br><br>
				<button type="submit">Log in<br>
			</form>
		</div>
	</body>
</html>