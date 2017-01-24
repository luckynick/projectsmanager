<html>
<head>
	<title>List of users</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>

<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div class='protector_of_the_realm'>
	<ul class='projList'>
		<?php
			while($user = mysqli_fetch_assoc($result))
			{
				?>
				<li>
					<div class="projInList" >
						<a href="\manager\user.php?id=
							<?php echo $user['user_id'] ?>">
						<img src="\res\<?php echo $user['image'] ?>" 
						alt="Preview" height="120">
						</a>
						<div>
						User: <a class='blue' href="\manager\user.php?id=
						<?php echo $user['user_id'] . '">' 
							. $user['username'] ?> </a><br>
						Name and surname: 
						<?php if(!empty($user['first_name']) 
									and !empty($user['last_name'])) { 
								echo $user['first_name'] . " " 
									. $user['last_name'];
							} else {
								echo 'not specified.';
						} ?>
						<br>Registration date:
						<?php echo $user['registration_date']; ?>
						<br>
						
						</div>
					</div>
				</li>
				<?php
			}
		?>
		<div style="clear: both;"></div>
	</ul>
	</div>
</body>
</html>