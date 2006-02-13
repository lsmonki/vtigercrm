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
<FORM METHOD="post" action="index.php?module=Products&action=add2db" enctype="multipart/form-data">
	<?php
		$ret_action = $_REQUEST['return_action'];
		$ret_module = $_REQUEST['return_module']; 
		$ret_id = $_REQUEST['return_id'];

	?>
<B>
<?php echo $ret_module.' : '.$mod_strings['LBL_ATTACH_FILE']; ?>
</B>
<hr>
<br>
 <INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="100000">
 <INPUT TYPE="hidden" NAME="return_module" VALUE="<?php echo $ret_module ?>">
 <INPUT TYPE="hidden" NAME="return_id" VALUE="<?php echo $ret_id ?>">
 <TABLE BORDER="0" cellspacing="2" cellpadding="2">
 <TR>
   <TD><div align="right"><?php echo $mod_strings['LBL_FILE']; ?> </TD>
   <TD><INPUT TYPE="file" NAME="binFile"></TD>
 </TR>
 <TR>
   <TD width="25%" valign="top"><div align="right"><?php echo $mod_strings['LBL_DESCRIPTION']; ?> </div></TD>
   <TD width="75%"><TEXTAREA NAME="txtDescription" ROWS="3" COLS="50"></TEXTAREA></TD>
  </TR>
  <TR>
   <TD></TD>	
   <TD><INPUT TYPE="submit" VALUE="<?php echo $mod_strings['LBL_UPLOAD']; ?>"></TD>
  </TR>
 </TABLE>
		
</FORM>
</BODY>
</HTML>
