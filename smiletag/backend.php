<?php
	require_once('lib/domit/xml_domit_lite_include.php');
	require_once('lib/St_XmlParser.class.php');	
	require_once('lib/St_ConfigManager.class.php');
	require_once('lib/St_FileDao.class.php');
	require_once('lib/St_PersistenceManager.class.php');
	
	session_start();
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");		        // expires in the past
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");     // Last modified, right now
	header("Cache-Control: no-cache, must-revalidate");	        // Prevent caching, HTTP/1.1
	header("Pragma: no-cache");
	
	//query the latest message timestamp from persistence
	
	//compare it with the last timestamp in the session
	//if there is no session,then create it
	
	//if the timestamp is different, then send 1
	//so the iframe should reload
	//load data from persistence object
	
	$configManager		=& new St_ConfigManager();
	$persistenceManager =& new St_PersistenceManager();
	$storageType 		=  $configManager->getStorageType();
			
					
	if(strtolower($storageType) == 'file'){
		$fileName = $configManager->getDataDir().'/'.$configManager->getMessageFileName();
		$persistenceManager->setStorageType('file');
		$persistenceManager->setMessageFile($fileName);
	}elseif(strtolower($storageType) == 'mysql'){
		die("MySQL storage type, not implemented yet!");
	}else{
		die("Unknown storage type!");
	}
			
	$latestTimeStamp = $persistenceManager->getLatestTimestamp();
	
		
	if(empty($_SESSION['timestamp'])){
		$_SESSION['timestamp'] = $latestTimeStamp;
	}
	
	
	if($_SESSION['timestamp'] != $latestTimeStamp){
		$_SESSION['timestamp'] = $latestTimeStamp;
		echo '1'; //new message exist
	}else{
		echo '0';
	}
?>