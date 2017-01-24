<?php
	require_once('config.php');
	$salt = "gasdfjki"; //increase password reliability
	define("_HOME", dirname(dirname(__FILE__))); //home
	//directory of page, used in php code
	session_start();
	
	function project_res_path($file_name) //get relative
	//path to project resourses
	{
		return "\\res$file_name";
	}
	
	function user_res_path($file_name) //get relative
	//path to user resourses
	{
		return "\\res$file_name";
	}
	
	function get_extension($mime_type){ //resolve
	//mime and return extension
		$extensions = array('image/jpeg' => 'jpg',
							'image/png' => 'png',
							'application/pdf' => 'pdf',
							'text/plain' => 'txt'
							);
		return $extensions[$mime_type];
	}
	
	function delete_dir($dirPath) {
		if(!file_exists($dirPath)) return;
		if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::delete_dir($file);
			} else {
				unlink($file);
			}
		}
		rmdir($dirPath);
	}
?>