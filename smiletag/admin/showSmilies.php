<?php
	require_once('checkSession.php');
	require_once('../lib/domit/xml_domit_lite_include.php');
	require_once('../lib/St_XmlParser.class.php');	
	require_once('../lib/St_ConfigManager.class.php');


	//load smilies list
	$configManager   =& new St_ConfigManager();
	$smiliesPattern  = $configManager->getSmileyPattern();

	$fileName = $configManager->getDataDir().'/'.$configManager->getSmileyConfigFile();
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
                    <th width="18%" align="center" scope="col">Preview</th>
                    <th width="33%" align="center" scope="col">Image Name </th>
                    <th width="26%" align="center" scope="col">Smilie Code </th>
                    <th width="15%" align="center" scope="col">Delete</th>
                  </tr>
<?php
	if(!empty($smiliesPattern)){
		foreach ($smiliesPattern as $key=>$value){
			if($key % 2){
				$style = 'class="odd"';
			}else{
				$style = '';
			}
?>
                  <tr <?php echo $style; ?>>
                    <td align="center"><?php echo $key+1; ?></td>
                    <td align="center"><img src="../images/smilies/<?php echo $value['image']; ?>"></td>
                    <td align="center"><?php echo $value['image']; ?></td>
                    <td align="center"><strong><?php echo $value['pattern']; ?></strong></td>
                    <td align="center"><input name="smilie_codes[]" type="checkbox" value="<?php echo $value['pattern']; ?>"></td>
                  </tr>
<?php }} ?>                  
                  <tr>
                    <td colspan="5" align="right"><input name="submit" class="btn" type="submit" id="submit" value="Delete Selected"></td>
                    </tr>
                </table>
                <input type="hidden" name="action_input" value="smilies">
				<input type="hidden" name="action_target" value="smilies">
                </form><br />
				<form action="adminProcess.php" method="post">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" class="grid">
				  <tr>
				    <td align="center" nowrap><strong>Image Name </strong></td>
				    <td align="center"><input name="smilie_image" type="text" class="form" id="smilie_image" size="20"></td>
				    </tr>
				  <tr>
					<td width="50%" align="center" nowrap><strong>Smilie Code </strong></td>
					<td width="50%" align="center"><input name="smilie_code" type="text" class="form" id="smilie_code" size="20"></td>
					</tr>
				  
				  <tr>
					<td colspan="2" align="center"><input name="submit" class="btn" type="submit" id="submit" value="Add Smilie"></td>
					</tr>
				</table>
				<input type="hidden" name="action_input" value="smilies">
				<input type="hidden" name="action_target" value="smilies">
				</form>
				</td>
				</tr>
		    </table>		
	
	
	
