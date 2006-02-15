<?php
		
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');
	require_once('../lib/St_FileDao.class.php');
	require_once('../lib/St_PersistenceManager.class.php');
	require_once('../lib/St_InputProcessor.class.php');
	require_once('../lib/St_AdminManager.class.php');
	
	$GLOBALS['param'] = null;
	
	/** The main action handler **************************************************************/
	/*  Code below handle all submit form, and redirect the result to the appropriate target */
	/*****************************************************************************************/
	if(!empty($_POST['submit'])){
		
		if($_POST['action_input'] == 'messages'){
			$status = showMessageAction($_POST);
		}elseif($_POST['action_input'] == 'edit_message'){
			$status = editMessageAction($_POST);
		}elseif($_POST['action_input'] == 'moderation'){
			$status = showMessageModerationAction($_POST);
		}elseif($_POST['action_input'] == 'edit_message_moderation'){
			$status = editMessageModerationAction($_POST);
		}elseif($_POST['action_input'] == 'ban'){
			$status = banAction($_POST);
		}elseif($_POST['action_input'] == 'smilies'){
			$status = smiliesAction($_POST);
		}elseif($_POST['action_input'] == 'badwords'){
			$status = badwordsAction($_POST);
		}elseif($_POST['action_input'] == 'configuration'){
			$status = configurationAction($_POST);
		}
		
		if($status === true){
			actionRedirect($_POST['action_target'],$GLOBALS['param']);
		}else{
			actionRedirect($_POST['action_input'],$GLOBALS['param']);
		}
	}
	
	/**
	* Redirect to certain pages
	*/
	function actionRedirect($action_target,$param){
		
		if($action_target == 'messages'){
			$target_url = 'admin.php?show=messages';
		}elseif ($action_target == 'edit_message'){
			$target_url = 'admin.php?show=edit_message';
		}elseif ($action_target == 'moderation'){
			$target_url = 'admin.php?show=moderation';
		}elseif ($action_target == 'edit_message_moderation'){
			$target_url = 'admin.php?show=edit_message_moderation';
		}elseif ($action_target == 'ban'){
			$target_url = 'admin.php?show=ban';
		}elseif ($action_target == 'smilies'){
			$target_url = 'admin.php?show=smilies';
		}elseif ($action_target == 'badwords'){
			$target_url = 'admin.php?show=badwords';
		}elseif ($action_target == 'configuration'){
			$target_url = 'admin.php?show=configuration';
		}

		if(!empty($param)){
			$target_url .= $param;
		}
			
		header("Location: http://" . $_SERVER['HTTP_HOST']
                     . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
                     . "/" . $target_url);

	}
	
	
	/************************************************************************/
	/* Below are handler function for each event triggered by above			*/
	/************************************************************************/
	
	/**
	 * Action for showing message
	 */ 
	function showMessageAction($inputData){
		$adminManager =& new St_AdminManager();
		
		if($inputData['submit'] == 'Delete All'){
			$adminManager->deleteAllMessages();
			$_SESSION['SMILETAG_MESSAGE'] = 'Messages successfully deleted!';	
		}else{
			
			
			//delete messages which timestamp contained in $inputData
			if(isset($inputData['timestamp']) && is_array($inputData['timestamp'])){
				$adminManager->deleteMessages($inputData['timestamp']);
				$_SESSION['SMILETAG_MESSAGE'] = 'Messages successfully deleted!';
			}else{
				$_SESSION['SMILETAG_MESSAGE'] = 'You must select at least one message to delete!';
			}
		}
		
		return true;
	}
	
	/**
	 * Action for edit message form
	 */ 
	function editMessageAction($inputData){
		//validation, all fields required
		if(empty($inputData['name']) or empty($inputData['message']) or empty($inputData['ipaddress'])){
			
			$GLOBALS['param'] 			  = '&id='.$inputData['datetime'];
			$_SESSION['SMILETAG_MESSAGE'] = 'IP Address, Name and Message required!';
			
			return false;
		}
		
		$currentMessage['name'] 		= trim($inputData['name']);
		$currentMessage['message'] 		= trim($inputData['message']);
		$currentMessage['url'] 			= trim($inputData['mail_or_url']);
		$currentMessage['ipaddress'] 	= trim($inputData['ipaddress']);
		$currentMessage['datetime'] 	= trim($inputData['datetime']);
		
		$adminManager =& new St_AdminManager();
		$adminManager->updateMessage($currentMessage);
		
		$_SESSION['SMILETAG_MESSAGE'] = 'Messages successfully updated!';	
		
		return true;
	}
	
	/**
	 * Action for message moderation
	 */ 
	function showMessageModerationAction($inputData){
		
		if(isset($inputData['timestamp'])){
			$adminManager =& new St_AdminManager();
			
			$adminManager->moderateMessages($inputData['timestamp']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Messages succesfully moderated!';
		}else{
			$_SESSION['SMILETAG_MESSAGE'] = 'No messages to be moderated!';
		}	
		return true; 
	}
	
	/**
	 * Action for edit moderated message form
	 */ 
	function editMessageModerationAction($inputData){
		//validation, all fields required
		if(empty($inputData['name']) or empty($inputData['message']) or empty($inputData['ipaddress'])){
			
			$GLOBALS['param'] 			  = '&id='.$inputData['datetime'];
			$_SESSION['SMILETAG_MESSAGE'] = 'IP Address, Name and Message required!';
			
			return false;
		}
		
		$currentMessage['name'] 		= trim($inputData['name']);
		$currentMessage['message'] 		= trim($inputData['message']);
		$currentMessage['url'] 			= trim($inputData['mail_or_url']);
		$currentMessage['ipaddress'] 	= trim($inputData['ipaddress']);
		$currentMessage['datetime'] 	= trim($inputData['datetime']);
		
		$adminManager =& new St_AdminManager();
		$adminManager->updateMessage($currentMessage,'moderation');
		
		$_SESSION['SMILETAG_MESSAGE'] = 'Messages successfully updated!';	
		
		return true;
	}
	
	/**
	 * Action for banning ip address and nickname
	 */ 
	function banAction($inputData){
		$adminManager =& new St_AdminManager();
		
		//if button for submit an ip address pressed
		if($inputData['submit'] == 'Ban This IP'){
			
			//validation for empty and invalid ip address
			$num="(\\d|[1-9]\\d|1\\d\\d|2[0-4]\\d|25[0-5])";
			
			if(empty($inputData['ipaddress']) or !preg_match("/^$num\\.$num\\.$num\\.$num$/", $inputData['ipaddress'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must enter an IP Address with correct format!';
				return false;
			}
			
			$adminManager->banIpAddress($inputData['ipaddress']);
			$_SESSION['SMILETAG_MESSAGE'] = 'IP Address '.$inputData['ipaddress'].' successfully banned!';	
		
		}elseif($inputData['submit'] == 'Ban This Name'){
			
			if(empty($inputData['name'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must enter a name!';
				return false;
			}
			
			$adminManager->banNickName($inputData['name']);
			$_SESSION['SMILETAG_MESSAGE'] = $inputData['name'].' successfully banned!';							
		}elseif($inputData['submit'] == 'Delete Selected IP'){ //action for deleting ip address entry
			
			if(empty($inputData['ipaddress'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must select at least one IP!';
				return false;
			}
			
			$adminManager->deleteIpAddress($inputData['ipaddress']);
			$_SESSION['SMILETAG_MESSAGE'] = 'IP Address successfully deleted!';							
		}elseif($inputData['submit'] == 'Delete Selected Name'){ //action for deleting nickname entry
			
			if(empty($inputData['names'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must select at least one Name!';
				return false;
			}
			
			$adminManager->deleteNickName($inputData['names']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Name(s) successfully deleted!';							
		}
		
		return true;
	}
	
	/**
	 * Action for managing smilies
	 */ 
	function smiliesAction($inputData){
		$adminManager =& new St_AdminManager();
		
		//if button for submit smilie code pressed
		if($inputData['submit'] == 'Add Smilie'){
			
			if(empty($inputData['smilie_image']) or empty($inputData['smilie_code'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'Image Name and Smilie Code required!';
				return false;
			}
			
			$adminManager->addSmilieCode($inputData['smilie_code'],$inputData['smilie_image']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Smilie code successfully added!';							
		
		}elseif($inputData['submit'] == 'Delete Selected'){
			
			if(empty($inputData['smilie_codes'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must select at least one smilie code';
				return false;
			}
						
			$adminManager->deleteSmilieCode($inputData['smilie_codes']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Smilie code successfully deleted!';							
		
		}
		
		return true;
	}
	
	/**
	 * Action for managing badwords
	 */ 
	function badwordsAction($inputData){
		$adminManager =& new St_AdminManager();
		
		//if button for submit badword pressed
		if($inputData['submit'] == 'Add Word'){
			
			if(empty($inputData['badword'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'Bad Word required!';
				return false;
			}
			
			$adminManager->addBadword($inputData['badword']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Bad Word successfully added!';							
		
		}elseif($inputData['submit'] == 'Delete Selected'){
			
			if(empty($inputData['badwords'])){
				$_SESSION['SMILETAG_MESSAGE'] = 'You must select at least one word!';
				return false;
			}
						
			$adminManager->deleteBadword($inputData['badwords']);
			$_SESSION['SMILETAG_MESSAGE'] = 'Badword successfully deleted!';							
		
		}
		
		return true;
	}
	
	/**
	 * Action for managing global configuration
	 */ 
	function configurationAction($inputData){
		$adminManager =& new St_AdminManager();
		
		unset($inputData['submit']);
		unset($inputData['action_input']);
		unset($inputData['action_target']);
		
		$adminManager->updateConfiguration($inputData);
		
		$_SESSION['SMILETAG_MESSAGE'] = 'Configuration updated!';							
				
		return true;
	}
?>