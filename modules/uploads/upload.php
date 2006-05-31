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
<?
global $theme;
$theme_path="themes/".$theme."/";
?>
<HTML>
<head>
	<link type="text/css" href="<? echo $theme_path;?>style.css" rel="stylesheet">
</head>
<BODY>
<FORM METHOD="post" action="index.php?module=uploads&action=add2db&return_module=<?php echo $_REQUEST['return_module']?>" enctype="multipart/form-data">
<?php
	$ret_action = $_REQUEST['return_action'];
	$ret_module = $_REQUEST['return_module']; 
	$ret_id = $_REQUEST['return_id'];

?>

<INPUT TYPE="hidden" NAME="MAX_FILE_SIZE" VALUE="1000000">
<INPUT TYPE="hidden" NAME="return_module" VALUE="<?php echo $ret_module ?>">
<INPUT TYPE="hidden" NAME="return_action" VALUE="<?php echo $ret_action ?>">
<INPUT TYPE="hidden" NAME="return_id" VALUE="<?php echo $ret_id ?>">
<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
<tr>
<td class="genHeaderSmall" align="left"><? echo $mod_strings["LBL_ATTACH_FILE"];?></td>
<td width="70%" align="right">&nbsp;</td>

</tr>
<tr><td colspan="2"><hr /></td></tr>
<tr>
<td width="30%" colspan="2" align="left"><b><? echo $mod_strings["LBL_STEP_SELECT_FILE"];?></b><br>
<? echo $mod_strings["LBL_BROWSE_FILES"]; ?>
</td>
</tr>
<tr>
<td width="30%" colspan="2" align="left"><input type="file" name="filename"/></td>

</tr>
<tr><td colspan="2">&nbsp;</td></tr>
<tr>
<td width="30%" colspan="2" align="left"><b> <? echo $mod_strings["LBL_DESCRIPTION"];?> </b><? echo $mod_strings["LBL_OPTIONAL"];?></td>
</tr>
<tr><td colspan="2" align="left"><textarea cols="50" rows="5"  name="txtDescription" class="txtBox"></textarea></td></tr>
<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>

<tr>
<td colspan="2" align="center">
<input type="submit" name="save" value=" &nbsp;Attach&nbsp; " class="classBtn" />&nbsp;&nbsp;
<input type="button" name="cancel" value=" Cancel " class="classBtn" onclick="self.close();" />
</td>
</tr>
<tr><td colspan="2" style="border-top:1px dashed #CCCCCC;">&nbsp;</td></tr>
</table>

</FORM>
</BODY>
</HTML>
