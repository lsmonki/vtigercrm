<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/

	
require_once('Smarty_setup.php');
global $mod_strings,$app_strings,$adb,$theme;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("RETURN_MODULE","Settings");
$smarty->assign("RETURN_ACTION","");

function getListLeadMapping($image_path)
{
	global $adb;
	$sql="select * from vtiger_convertleadmapping";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i =0;$i <$noofrows;$i++)
	{
		$leadid = $adb->query_result($result,$i,'leadfid');
		$accountid = $adb->query_result($result,$i,'accountfid');
		$contactid = $adb->query_result($result,$i,'contactfid');
		$potentialid = $adb->query_result($result,$i,'potentialfid');
		$cfmid = $adb->query_result($result,$i,'cfmid');

		$display_val .= '<tr height="20" class="Datafield">';
		$sql1="select fieldlabel from vtiger_field where fieldid ='".$leadid."'";
		$result1 = $adb->query($sql1);
		$leadfield = $adb->query_result($result1,0,'fieldlabel');
		$label['leadlabel'] = $leadfield;
		$sql2="select fieldlabel from vtiger_field where fieldid ='".$accountid."'";
		$result2 = $adb->query($sql2);
		$accountfield = $adb->query_result($result2,0,'fieldlabel');
		$label['accountlabel'] = $accountfield;
		
		$sql3="select fieldlabel from vtiger_field where fieldid ='".$contactid."'";
		$result3 = $adb->query($sql3);
		$contactfield = $adb->query_result($result3,0,'fieldlabel');
		$label['contactlabel'] = $contactfield;
		$sql4="select fieldlabel from vtiger_field where fieldid ='".$potentialid."'";
		$result4 = $adb->query($sql4);
		$potentialfield = $adb->query_result($result4,0,'fieldlabel');
		$label['potentiallabel'] = $potentialfield;
		if($accountfield !=''&& $contactfield !='' && $potentialfield!='')
		{	
			$label['del']='<img src="'.$image_path.'delete.gif" border="0" onClick="confirmdelete(\'index.php?action=DeleteLeadCustomFieldMapping&module=Settings&id='.$cfmid.'&return_module=Settings&return_action=ListCustomFieldMapping\');" alt="Delete" title="Delete"/>';
		}else
		{
			$label['del']= '';
		}
		$mapping[]=$label;
	}
	return $mapping;
}

$display_fields = getListLeadMapping($image_path);
	if (isset($display_fields))
 	       $smarty->assign("CUSTOMFIELDMAPPING",$display_fields);

$smarty->display("ListLeadCustomFieldMapping.tpl");

?>
