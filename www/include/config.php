<?php 
	//this file isconfiguration for site
	$config = array();
	//domain of site
	$config['url'] = 'proman';
	//Number of displayed projects
	//in list. Doesn't apply to 
	//result of search.
	$config['per_page'] = 3;
	//parameters for connection to database
	$config['db'] = array(
		'location' => 'localhost', 
		'user' => 'root', 
		'password' => '', 
		'name' => 'projectsmanager'
	);
	//description word for 'length' field
	$config['length_unit'] = "days"; 
	//max mark which contributor can get for project
	$config['mark_limit'] = 100; 
	//path to logo image
	$config['logo'] = "\\res\logo.png";
	$config['keywords'] = array(
		'grandpa' => 365, //365
		'hardworking' => 5, //5
		'reliable' => 60, //60
		'num_of_mates_high' => 3, //3
		'num_of_mates_low' => 2 //2
	);
?>