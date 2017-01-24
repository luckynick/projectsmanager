<?php 
	require_once('..\include\permission.php');
	require_once('..\include\base.php');
	if(empty($_GET['action']))
	{
		require_once("search_form.php");
		exit;
	}
	$what_to_search = $_POST['what_search']; //users or projects
	$direction = $_POST['direction']; //descending or ascending ordering
	$parameters = ""; //here will be list of parameters for query
	//add queries below
	if($what_to_search == "users")
	{
		$order_by = $_POST['order_usr'];
		if(!empty($_POST['username'])) $parameters .= "LOWER(`username`)='" 
			. strtolower(addslashes($_POST['username'])) . "'* ";
		if(!empty($_POST['first_name'])) $parameters .= "*LOWER(`first_name`)='" 
			. strtolower(addslashes($_POST['first_name'])) . "'* ";
		if(!empty($_POST['last_name'])) $parameters .= "*LOWER(`last_name`)='" 
			. strtolower(addslashes($_POST['last_name'])) . "'* ";
		if(!empty($_POST['keywords'])) $parameters .= "*LOWER(`keywords`) LIKE '%" 
			. strtolower(addslashes($_POST['keywords'])) . "%'* ";
	}
	else
	{
		$order_by = $_POST['order_pro'];
		if(!empty($_POST['title'])) $parameters .= "LOWER(`title`) LIKE '%" 
			. strtolower(addslashes($_POST['title'])) . "%'* ";
		if(!empty($_POST['customer'])) $parameters .= "*LOWER(`customer`) LIKE '%" 
			. strtolower(addslashes($_POST['customer'])) . "%'* ";
		if(!empty($_POST['cost'])) $parameters .= "*`cost`='" . $_POST['cost'] . "'* ";
		if(!empty($_POST['length'])) $parameters .= "*`length`='" 
			. $_POST['length'] . "'";
	}
	if(!empty($parameters)) //All elements of search will be displayed if 
	//user didn't provide any parameters for search. Otherwise parameters
	//will apply.
	{
		//insert commas between parameters
		$parameters = "WHERE " . str_replace("*", "", str_replace("* *", " AND ", $parameters));
	}
	//get result of search
	if($what_to_search == "users")
	{
		$result = $conn->query("SELECT `user_id`, `username`, `image`, " 
			. "LEFT(`description`, 100) AS `description`, "
			. "`registration_date`, `first_name`, `last_name` " 
			. "FROM `$what_to_search` $parameters "
			. "ORDER BY `$order_by` $direction;");
		$file = 'search';
		require_once("user_list.php");
	}
	else
	{
		$_SESSION['query'] = "SELECT `id`, `title`, `image`, LEFT(`description`, 100) " 
			. "AS `description`, `customer`, `cost`, `pub_date` FROM `$what_to_search` " 
			. "$parameters ORDER BY `$order_by` $direction;";
		$result = $conn->query($_SESSION['query']);
		$file = 'search';
		require_once("proj_list.php");
	}
?>
