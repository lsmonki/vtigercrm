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
		$fileName = $configManager->getDataDir().'/'.$configManager->getMessageFileName();
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
	
	//get all message into array
	$messageArray = $persistenceManager->getMessageArray(0);
	
	//create the formatted date/time and adjust the path to the smilies
	if(!empty($messageArray)){
		foreach ($messageArray as $key=>$value){
			eval("\$datetime = ".$value['datetime']." ".$timeSign." (".$timeDiff."*3600);");
				
			$messageArray[$key]['date']    =  gmdate($dateFormat,$datetime);
			$messageArray[$key]['time']    =  gmdate($timeFormat,$datetime);
			$messageArray[$key]['message'] =  str_replace('<img src="images/smilies/','<img src="../images/smilies/',$messageArray[$key]['message']);
		}
	}
	
	/*********************************************************************/
	require_once('localMessage.php');
?>	
    	  <form action="adminProcess.php" method="post">
		   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
			  <tr class="odd">
				<th width="2%" scope="col">No</th>
			    <th width="14%" scope="col">IP Address </th>
				<th width="10%" scope="col">Name</th>
				<th width="16%" scope="col">Timestamp</th>
				<th width="35%" scope="col">Message</th>
				<th colspan="2" scope="col">&nbsp;</th>
				</tr>
<?php
  if(!empty($messageArray)){
	$row = 0;
  	foreach ($messageArray as $key=>$value){
		if($key % 2){
			$style = 'class="odd"';
		}else{
			$style = '';
		}

		$row++;	
?>
			 <tr <?php echo $style; ?>>
			    <td align="center"><?php echo $row; ?></td>
				<td align="center"><?php echo $value['ipaddress']; ?></td>
				<td align="center"><?php echo $value['url']; ?></td>
				<td align="center"><?php echo $value['time'].'<br />'.$value['date']; ?></td>
				<td><div align="justify"><?php echo $value['message']; ?></div></td>
				<td width="8%" align="center"><a href="admin.php?show=edit_message&id=<?php echo $value['datetime']; ?>">Edit</a></td>
				<td width="5%" align="center"><input type="checkbox" name="timestamp[]" value="<?php echo $value['datetime']; ?>"></td>
			  </tr>
<?php
	}
  }
?>			  
			  
			  <tr>
			    <td colspan="7" align="right"><input type="submit" name="submit" class="btn" value="Delete All" onclick="javascript:return confirm('Are you sure you want to Delete All Messages?')">&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="submit" value="Delete Selected" onclick="javascript:return confirm('Are you sure you want to Delete Selected Messages?')" class="btn"></td>
			    </tr>
			</table>
			<input type="hidden" name="action_input" value="messages">
			<input type="hidden" name="action_target" value="messages">
		   </form>
	
