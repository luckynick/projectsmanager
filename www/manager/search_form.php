<html>
<head>
	<title>Search</title>
	<link href="\include\style.css" rel="stylesheet" type="text/css">
</head>
<body>
	<?php require_once('..\include\topbar.php'); ?>
	<div style='left: 40%; top:30%;' class='form registration'>
		Search<br><br>
		<form style="width: 400px;" action="search.php?action=done" method="POST">
			<input type='hidden' name='type' value='done'>
			What to search: 
			<input id='project_select' type="radio" name="what_search" 
				value='projects' checked>Project
			<input id='user_select' type="radio" name="what_search" 
				value='users'>User <br>
			<div class='checkboxes'  id='project_select'>
				Order by:<br> 
				<input type="radio" name="order_pro" 
					value='title' checked>Title<br>
				<input type="radio" name="order_pro" 
					value='pub_date'>Publication date<br>
				<input type="radio" name="order_pro" 
					value='cost'>Cost<br>
				<input type="radio" name="order_pro" 
					value='length'>Length<br>
			</div>
			<div class='checkboxes'  id='user_select'>
				Order by:<br> 
				<input type="radio" name="order_usr" 
					value='username' checked>Username<br>
				<input type="radio" name="order_usr" 
					value='registration_date'>Registration date<br>
				<input type="radio" name="order_usr" 
					value='first_name'>First name<br>
				<input type="radio" name="order_usr" 
					value='last_name'>Last name<br>
			</div><br><br>
			<input type="radio" name="direction" 
				value='DESC' checked>Descending<br>
			<input type="radio" name="direction" 
				value=''>Ascending<br>
			<br>
			Leave some of below fields empty if<br>
			you don't want to use them as search parameter.<br>
			<div id='project_select'>
				Title: <input name="title" type="text" 
					value="<?php echo $_POST['title'] ?>"> <br><br>
				Customer: <input name="customer" type="text" 
					value="<?php echo $_POST['customer'] ?>"> <br><br>
				Cost (in dollars): <input name="cost" type="text" 
					maxlength=20 value="<?php echo $_POST['cost'] ?>"> <br><br>
				Length: <input name="length" type="text" 
					maxlength=6 value="<?php echo $_POST['length'] ?>"><br><br>
			</div>
			<div id='user_select'>
				Username: <input name="username" type="text" 
					value="<?php echo $_POST['username'] ?>"> <br><br>
				First name: <input name="first_name" type="text" 
					value="<?php echo $_POST['first_name'] ?>"> <br><br>
				Last name: <input name="last_name" type="text" 
					value="<?php echo $_POST['last_name'] ?>"> <br><br>
				Keyword: <input name="keywords" type="text" 
					value="<?php echo $_POST['keywords'] ?>"><br><br>
			</div><br>
			<button type="submit">Search</button><br>
		</form>
	</div>
</body>
</html>