<div class='topbar'>
	<ul>
		<?php 
			if(isset($_SESSION['logged_user']))
			{
				require_once('base.php');
				$id_a = $conn->query("SELECT `user_id` FROM `users` " 
					. "WHERE `username`='" . $_SESSION['logged_user'] . "';")
					->fetch_assoc();
				$u_id = $id_a['user_id'];
				?>
				<li>
					<a href='\ '><img style='margin: 0 5px 0 0;' height=20 
						src='<?php echo $config['logo']; ?>' alt="Logo">
						<?php echo $config['url']; ?>
					</a>
				</li>
				<li><a href='\manager\list.php?page=1'>List of projects</a></li>
				<li><a href='\manager\newproject.php'>Add project</a></li>
				<li><a href='\manager\search.php'>Search</a></li>
				<li style='float:right;'><a href='\logout.php'>Log out</a>
				(<?php echo "<a href='\manager\user.php?id=$u_id'>" 
					. $_SESSION['logged_user'] . "</a>";?>)</li>
				<li style='float:right;'><a href='\settings.php'>Settings</a></li>
			<?php }
			else
			{?>
				<li>
					<a href='\ '><img style='margin: 0 5px 0 0;' height=20 
						src='<?php echo $config['logo']; ?>' alt="Logo">
						<?php echo $config['url']; ?>
					</a>
				</li>
			<?php }
		?>
	</ul>
	<hr style='margin: 0 0 0 0;'>
</div>