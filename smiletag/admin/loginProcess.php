<?php
	session_start();
	
	require('password.php');
	
	if(!empty($_GET['logout'])){
		$_SESSION = array();
		session_destroy();
		$target_url = 'index.php';
	
	}else if(!empty($_POST['submit'])){
	
		$input_username = trim($_POST['username']);
		$input_password = trim($_POST['password']);
		
		if(($input_username == $Username) && ($input_password == $Password)){
			$_SESSION['SMILETAG_LOGGED'] = true;
			$target_url = 'admin.php?show=messages';
		}else{
			$_SESSION['SMILETAG_LOGIN_ERROR'] = true;
			$target_url = 'index.php';
		}
	}
	
	header("Location: http://" . $_SERVER['HTTP_HOST']
                     . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
                     . "/" . $target_url);
?>