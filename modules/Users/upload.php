<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
* 
 ********************************************************************************/
-->



<HTML>
<BODY>
<FORM METHOD="post" action="index.php?module=Users&action=add2db" enctype="multipart/form-data">
	<?php
		$ret_action = "index";
                $ret_module = "Settings"; 
//$ret_id = $_REQUEST['return_id'];

	?>
<?php echo get_module_title($mod_strings['LBL_MODULE_NAME'],$mod_strings['LBL_ATTACH_FILE'],false);?>
<br>
 <INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="100000">
 <INPUT TYPE="hidden" NAME="return_module" VALUE="<?php echo $ret_module ?>">
 <INPUT TYPE="hidden" NAME="return_id" VALUE="<?php echo $ret_id ?>">
 <TABLE width="50%" BORDER="0" cellspacing="0" cellpadding="0" class="formOuterBorder">
 <tr>
 	<td class="formSecHeader"><?php echo $mod_strings['LBL_FILE_INFORMATION']; ?></td>
</tr>
 	<td> 
	<TABLE BORDER="0" cellspacing="1" cellpadding="5">
	<TR>
	   <TD class="dataLabel"><?php echo $mod_strings['LBL_MODULENAMES']; ?>:</TD>
	   <TD><SELECT name="target_module">
	    <option value="Leads"><?php echo $app_strings['COMBO_LEADS'];?></option>
	    <option value="Accounts"><?php echo $app_strings['COMBO_ACCOUNTS'];?></option>
	    <option value="Contacts"><?php echo $app_strings['COMBO_CONTACTS'];?></option>
         <option value="HelpDesk"><?php echo $app_strings['COMBO_HELPDESK'];?></option>
	</SELECT>
	</TD> 
  </TR>
  <TR>
   <TD class="dataLabel"><?php echo $mod_strings['LBL_FILE']; ?>:</TD>
   <TD><INPUT TYPE="file" NAME="binFile"></TD>
  </TR>
    <TR>
   <TD width="25%" valign="top" class="dataLabel"><?php echo $mod_strings['LBL_DESCRIPTION']; ?>:</TD>
   <TD width="75%"><TEXTAREA NAME="txtDescription" ROWS="3" COLS="50"></TEXTAREA></TD>
  </TR>
       </TABLE></td>
 </tr>
 </table>
 <br>
  <TABLE width="50%" BORDER="0" cellspacing="0" cellpadding="0">
  <TR>
   <TD><div align="center">
          <INPUT class="button" TYPE="submit" VALUE="<?php echo $mod_strings['LBL_UPLOAD']; ?>">
        </div></TD>
  </TR>
 </TABLE>
</FORM>
</BODY>
</HTML>
