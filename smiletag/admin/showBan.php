<?php
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');
	
	//load ban list (ip address and nickname) 
	$configManager 	    =& new St_ConfigManager();
	
	$bannedIpAddress = $configManager->getBannedIpAddress();
	$bannedNickname  = $configManager->getBannedNickname();
	
	$fileName = $configManager->getDataDir().'/'.$configManager->getBanFileName();
	if(!is_writable($fileName)){
			$LOCAL_MESSAGE = "File $fileName is not writable! Please change the permission.";
	}
	/*********************************************************************/
	require_once('localMessage.php');
?>	
		  <table width="100%" border="0" cellspacing="0" cellpadding="5">
			 <tr valign="top">
				<td width="50%">
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
                  <tr class="odd">
                    <th width="14%" align="center" scope="col">No</th>
                    <th width="61%" align="center" scope="col">IP Address </th>
                    <th width="25%" align="center" scope="col">Delete</th>
                  </tr>
<?php 
	if(!empty($bannedIpAddress)){
		foreach ($bannedIpAddress as $key=>$value){
			if($key % 2){
				$style = 'class="odd"';
			}else{
				$style = '';
			}
		
?>                  
                  <tr <?php echo $style; ?>>
                    <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><?php echo $value; ?></td>
                    <td align="center"><input name="ipaddress[]" type="checkbox" id="ipaddress[]" value="<?php echo $value; ?>"></td>
                  </tr>
<?php }} ?>                                    
                  <tr>
                    <td colspan="3" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Delete Selected IP"></td>
                    </tr>
                </table>
                <input type="hidden" name="action_input" value="ban">
				<input type="hidden" name="action_target" value="ban">
                </form><br />
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
				  <tr>
					<td width="50%" align="center" nowrap><strong>IP Address </strong></td>
					<td width="50%" align="center"><input name="ipaddress" type="text" class="form" id="ipaddress" size="20"></td>
					</tr>
				  
				  <tr>
					<td colspan="2" align="center"><input name="submit" type="submit" id="submit" value="Ban This IP" class="btn"></td>
					</tr>
				</table>
				<input type="hidden" name="action_input" value="ban">
				<input type="hidden" name="action_target" value="ban">
				</form>
				</td>
				<td width="50%">
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
                  <tr class="odd">
                    <th width="14%" align="center" scope="col">No</th>
                    <th width="61%" align="center" scope="col">Name</th>
                    <th width="25%" align="center" scope="col">Delete</th>
                  </tr>
<?php 
	if(!empty($bannedNickname)){
		foreach ($bannedNickname as $key=>$value){
			if($key % 2){
				$style = 'class="odd"';
			}else{
				$style = '';
			}
		
?>                   
                  <tr <?php echo $style; ?>>
                    <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><?php echo $value; ?></td>
                    <td align="center"><input name="names[]" type="checkbox" id="names[]" value="<?php echo $value; ?>"></td>
                  </tr>
<?php }} ?>                  
                  <tr>
                    <td colspan="3" align="right"><input name="submit" type="submit" class="btn" id="submit" value="Delete Selected Name"></td>
                    </tr>
                </table>
                <input type="hidden" name="action_input" value="ban">
				<input type="hidden" name="action_target" value="ban">
                </form>
                <br />
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
				  <tr>
					<td width="50%" align="center" nowrap><strong>Name</strong></td>
					<td width="50%" align="center"><input name="name" type="text" class="form" size="20"></td>
					</tr>
				  
				  <tr>
					<td colspan="2" align="center"><input name="submit" class="btn" type="submit" id="submit" value="Ban This Name"></td>
					</tr>
				</table>
				<input type="hidden" name="action_input" value="ban">
				<input type="hidden" name="action_target" value="ban">
				</form>
				</td>
			 </tr>
		    </table>			
	
	
	
