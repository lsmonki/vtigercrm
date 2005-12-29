<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once ($theme_path."layout_utils.php");
global $mod_strings;

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
	<td>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		   <tr>
			<td class="tabStart">&nbsp;&nbsp;</td>
		        <td class="tabOff" nowrap><a href="index.php?module=HelpDesk&action=index" class="tabLink"><?php echo $mod_strings['LBL_TICKETS']; ?></a></td>
		        <td class="tabOn" nowrap><a href="index.php?module=Faq&action=index&faq=true" class="tabLink"><?php echo $mod_strings['LBL_FAQ']; ?></a></td>
			<td width="100%" class="tabEnd">&nbsp;</td>
		   </tr>
		</table>
	</td>
   </tr>
   <tr>
	<td class="tabContent" style="padding:10">
		<div id="tabcontent1"><? include ('modules/'.$_REQUEST['module'].'/ListView.php'); ?> </div>
	</td>
   </tr>
</table>
