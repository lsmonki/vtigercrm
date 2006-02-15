<?php
	session_start();
	
	if(!isset($_SESSION['SMILETAG_LOGGED']) or ($_SESSION['SMILETAG_LOGGED'] != true)){
		
		$target_url = 'index.php';
		
		header("Location: http://" . $_SERVER['HTTP_HOST']
                     . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
                     . "/" . $target_url);
	}
?>