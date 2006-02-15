<?php
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');
	require_once('../lib/St_FileDao.class.php');
	require_once('../lib/St_PersistenceManager.class.php');

	//load data from persistence object
	$configManager 	    =& new St_ConfigManager();
	$persistenceManager =& new St_PersistenceManager();
	$storageType 		=  $configManager->getStorageType();

						
	if(strtolower($storageType) == 'file'){
		$fileName = $configManager->getDataDir().'/'.$configManager->getModerationMessageFileName();
		$persistenceManager->setStorageType('file');
		$persistenceManager->setMessageFile($fileName);
		
		if(!is_writable($fileName)){
			$LOCAL_MESSAGE = "File $fileName is not writable! Please change the permission.";
		}
	}elseif(strtolower($storageType) == 'mysql'){
		die("MySQL storage type, not implemented yet!");
	}else{
		die("Unknown storage type!");
	}
			
	//load the datetime and timezone setting
	$timeZone   = $configManager->getTimeZone();
	$dateFormat = $configManager->getDateFormat();
	$timeFormat = $configManager->getTimeFormat();
	
	$timeNumber =  substr($timeZone,3);
			
	if(($timeNumber[0] == '+') or ($timeNumber[0] == '-')){
		$timeZone = $timeNumber;	
	}else{
		die("Invalid timezone setting! please make sure you have the correct setting in smiletag-config.xml, example: 'GMT+7'");
	}
	$timeSign = $timeZone[0];
	$timeDiff =  (int) substr($timeZone,1);
			
	//get message by timestamp id
	$id = trim($_GET['id']);
	
	$messageArray = $persistenceManager->getMessageById($id);
	
	if(empty($messageArray)){
		die('Invalid message id!');
	}
	
	eval("\$datetime = ".$messageArray['datetime']." ".$timeSign." (".$timeDiff."*3600);");
	$messageArray['date']    =  gmdate($dateFormat,$datetime);
	$messageArray['time']    =  gmdate($timeFormat,$datetime);
	
	//convert the smilies image to smilie code
	$messageArray['message'] =  preg_replace('/<img src=[^><]* alt="(.[^<]*)"\/>/i','$1', $messageArray['message']);
	
	//convert <b><i><u> into bbcode
	$patterns = array('/<b>(.*?)<\/b>/',
					  '/<u>(.*?)<\/u>/',
					  '/<i>(.*?)<\/i>/');
	$replacements = array('[b]\\1[/b]',
						  '[u]\\1[/u]',
						  '[i]\\1[/i]');
		
	$messageArray['message'] = preg_replace($patterns,$replacements, $messageArray['message']);
	
	//convert hypertext/ftp link
	$messageArray['message'] =  preg_replace('/<a href="(.[^<]*)" target="(.[^<]*)"[^><]*>(.*?)<\/a>/si','$1', $messageArray['message']);
	$messageArray['url'] 	 =  preg_replace('/<a href="(.[^<]*)" target="(.[^<]*)"[^><]*>(.*?)<\/a>/si','$1', $messageArray['url']);
	
	if($messageArray['name'] == $messageArray['url']){
		$messageArray['url'] = '';
	}
	
	//convert mailto link
	$messageArray['message'] =  preg_replace('/<a href="mailto:(.[^<]*)">(.*?)<\/a>/si','$1', $messageArray['message']);
	
	/*********************************************************************/
	require_once('localMessage.php');
?>	
    	  <form action="adminProcess.php" method="post">
		   <table width="50%" border="0" cellspacing="0" cellpadding="0" class="grid">
			  <tr class="odd">
				<th colspan="2" scope="col">Edit Message </th>
				</tr>
			  <tr>
				<td width="10%" align="center"><strong>Timestamp</strong></td>
				<td width="16%" align="left"><?php echo $messageArray['time'].' '.$messageArray['date']; ?></td>
				</tr>
			  <tr>
			    <td align="center"><strong>IP Address </strong></td>
			    <td align="left"><input name="ipaddress" type="text" class="form" id="ip_address" size="25" value="<?php echo $messageArray['ipaddress']; ?>"></td>
			    </tr>
			  <tr>
			    <td align="center"><strong>Name</strong></td>
			    <td align="left"><input name="name" type="text" class="form" id="name" size="25" value="<?php echo $messageArray['name']; ?>"></td>
				</tr>
			  <tr>
			    <td align="center"><strong>URL or Email</strong></td>
			    <td align="left"><input name="mail_or_url" type="text" class="form" id="name" size="25" value="<?php echo $messageArray['url']; ?>"></td>
				</tr>
			  <tr>
			    <td align="center"><strong>Message</strong></td>
			    <td align="left"><textarea name="message" cols="40" rows="4" class="form" id="message" wrap="virtual"><?php echo $messageArray['message']; ?></textarea></td>
				</tr>
			  <tr>
			    <td colspan="2" align="center"><input type="submit" name="submit" value="Save Message" class="btn"></td>
			    </tr>
			</table>
			<input type="hidden" name="datetime" value="<?php echo $messageArray['datetime']; ?>">
		 	<input type="hidden" name="action_input" value="edit_message_moderation">
			<input type="hidden" name="action_target" value="moderation">
		   </form>
	
