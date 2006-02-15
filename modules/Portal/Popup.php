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


global $app_strings;
global $app_list_strings;
global $mod_strings;
$portal_inputs='';
if(isset($_REQUEST['portalurl']) && $_REQUEST['portalurl']!='')
{
	$result=SavePortal($_REQUEST['portalname'],$_REQUEST['portalurl']);
	if($result == 'true')
	$portal_inputs.="<script>window.opener.location.href=window.opener.location.href;window.self.close();</script>";
}

	
	$portal_inputs.='<form name="NewPortal"><input type="hidden" name="action" value="Popup"><input type="hidden" name="module" value="Portal">';
	$portal_inputs.='<table width="100%" border=0 cellspacing=2 cellpadding=2><tr><td>'.$mod_strings['LBL_ADD_PORTAL'].'</td><td></td></tr><tr><td>'.$mod_strings['LBL_NAME'].'</td><td><input type="text" name="portalname" class="textField" style="width:100%"/></td></tr>';
	$portal_inputs.='<tr><td>'.$mod_strings['LBL_SITE_URL'].'</td><td><input type="text" name="portalurl" class="textField" style="width:100%"/></td></tr>';
	$portal_inputs.='<table width="100%" border="0" cellpadding="5" cellspacing="5" class="buttonLayer"><tr>';
    $portal_inputs.='<td align=center><input type="submit" name="Button"  value="Add" class="button">'; 
	$portal_inputs.='<input type="button" value="Cancel" class="button" onClick="window.close()"/>'; 
	$portal_inputs.='</td></tr></table></form>';

echo $portal_inputs;

function SavePortal($portalname,$portalurl)
{

global $adb;
$portalid=$adb->getUniqueID('portal');
$portal_name=$_REQUEST['portalname'];
$portal_url=$_REQUEST['portalurl'];
$query="insert into portal values(".$portalid.",'".$portal_name."','".$portal_url."',0)";
$result=$adb->query($query);
return true;
}
?>
