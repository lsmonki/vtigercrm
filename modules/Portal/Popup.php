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

require_once('modules/Portal/Portal.php');
global $app_strings;
global $app_list_strings;
global $mod_strings;
global $adb;
if(isset($_REQUEST['record']) && $_REQUEST['record'] !='')
{
	$portalid = $_REQUEST['record'];
	$query="select * from vtiger_portal where portalid =$portalid";
	$result=$adb->query($query);
	$portalname = $adb->query_result($result,0,'portalname');
        $portalurl = $adb->query_result($result,0,'portalurl');		
}
$portal_inputs='';
$portal_inputs.='<div style="display:block;position:relative;" id="orgLay">
		<table border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody><tr>
			<td class="genHeaderSmall" align="left" width="30%">' .$mod_strings['LBL_ADD'] .' '.$mod_strings['LBL_BOOKMARK'].'</td>
			<td align="right" width="80%"><a href="javascript:fninvsh(\'orgLay\');"><img src="../themes/blue/images/close.gif" align="absmiddle" border="0"></a></td>
		</tr>
		<tr><td colspan="2"><hr></td></tr>
		<tr>

			<td align="right"><b>'.$mod_strings['LBL_BOOKMARK'].' ' .$mod_strings['LBL_URL'] .' </b></td>
			<td align="left"><input name="portalurl" id="portalurl" class="txtBox" value="'.$portalurl.'" type="text"></td>
		</tr>
		<tr>
			<td align="right"><b>'.$mod_strings['LBL_BOOKMARK'].' ' .$mod_strings['LBL_NAME'] .' </b></td>
			<td align="left"><input name="portalname" id="portalname" value="'.$portalname.'" class="txtBox" type="text"></td>
		</tr>
		<tr><td style="border-bottom: 1px dashed rgb(204, 204, 204);" colspan="2">&nbsp;</td></tr>

		<tr>
			<td colspan="2" align="center">
					<input name="save" value=" &nbsp;'.$app_strings['LBL_SAVE_BUTTON_LABEL'].'&nbsp; " class="crmbutton small save" onClick="SaveSite(\''.$portalid.'\')" type="button">&nbsp;&nbsp;
					<input name="cancel" value=" '.$app_strings['LBL_CANCEL_BUTTON_LABEL'].' " class="crmbutton small cancel" onclick="fninvsh(\'orgLay\');" type="button">
			</td>
		</tr>
		<tr><td colspan="2" style="border-top: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
	</tbody></table>
</div>';
	
echo $portal_inputs;


?>
