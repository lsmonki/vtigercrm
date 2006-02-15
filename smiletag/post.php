<?php
	session_start();	

	require_once('lib/domit/xml_domit_lite_include.php');
	require_once('lib/St_XmlParser.class.php');	
	require_once('lib/St_ConfigManager.class.php');
	require_once('lib/St_FileDao.class.php');
	require_once('lib/St_PersistenceManager.class.php');
	require_once('lib/St_RuleProcessor.class.php');
	require_once('lib/St_InputProcessor.class.php');
	require_once('lib/St_PostManager.class.php');
			
	$HttpRequest['name'] = trim($_POST['name']);
	$HttpRequest['url'] =  trim($_POST['mail_or_url']);
	$HttpRequest['message'] = trim($_POST['message']);
	
	$postManager =& new St_PostManager();
	$errorMessage = null;
	
	if(empty($HttpRequest['name']) or empty($HttpRequest['message'])){
		$errorMessage = "Name and Message is required!";
	}else{
		if($postManager->doPost($HttpRequest) == false){
			$errorMessage = $postManager->getErrorMessage();
		}
	}
	
	if(empty($errorMessage)){
		header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/view.php');
	}else{
		echo '<center>';
		echo $errorMessage;
		echo '<br/><br/><a href="view.php">[Back]</a></center>';
	}
	
	
	
?>