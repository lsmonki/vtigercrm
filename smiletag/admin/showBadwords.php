<?php
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');

	//load badwords list
	$configManager 	 =& new St_ConfigManager();
	$badwordArray  	 = $configManager->getBadwordPattern();

	$fileName = $configManager->getDataDir().'/'.$configManager->getBadwordFile();
	if(!is_writable($fileName)){
			$LOCAL_MESSAGE = "File $fileName is not writable! Please change the permission.";
	}	
	/*********************************************************************/
	require_once('localMessage.php');
?>	
	 <table width="50%" border="0" cellspacing="0" cellpadding="5">
			 <tr valign="top">
				<td>
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
                  <tr class="odd">
                    <th width="8%" align="center" scope="col">No</th>
                    <th width="77%" align="center" scope="col">Bad Word</th>
                    <th width="15%" align="center" scope="col">Delete</th>
                  </tr>
<?php
	if(!empty($badwordArray['badwords'])){
		foreach ($badwordArray['badwords'] as $key=>$value){
			if($key % 2){
				$style = 'class="odd"';
			}else{
				$style = '';
			}
?>
                  <tr <?php echo $style; ?>>
                    <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><?php echo $value; ?></td>
                    <td align="center"><input name="badwords[]" type="checkbox" value="<?php echo $value; ?>"></td>
                  </tr>
<?php }} ?>                  
                 <tr>
                    <td colspan="3" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Delete Selected"></td>
                    </tr>
                </table>
                <input type="hidden" name="action_input" value="badwords">
				<input type="hidden" name="action_target" value="badwords">
				</form><br />
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
				  <tr>
				    <td width="50%" align="center" nowrap><strong>Bad Word </strong></td>
				    <td width="50%" align="center"><input name="badword" type="text" class="form" size="20"></td>
				    </tr>
				  <tr>
					<td colspan="2" align="center"><input name="submit" class="btn" type="submit" id="submit" value="Add Word"></td>
					</tr>
				</table>
				<input type="hidden" name="action_input" value="badwords">
				<input type="hidden" name="action_target" value="badwords">
				</form>				
				</td>
				</tr>
		    </table>		
	
	
	
