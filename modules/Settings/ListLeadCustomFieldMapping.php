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

	
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_LEAD_MAP_CUSTOM_FIELD'], true);
echo '<br><br>';
echo $mod_strings['leadCustomFieldDescription'];
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate('modules/Settings/ListLeadCustomFieldMapping.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","");

function getListLeadMapping($image_path)
{
	global $adb;
	$sql="select * from convertleadmapping";
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
		$sql1="select fieldlabel from field where fieldid =".$leadid;
		$result1 = $adb->query($sql1);
		$leadfield = $adb->query_result($result1,0,'fieldlabel');
		$display_val .= '<td nowrap width="20%" valign="top" style="padding:0px 3px 0px 3px;">'.$leadfield.'</td>';
		
		$sql2="select fieldlabel from field where fieldid =".$accountid;
		$result2 = $adb->query($sql2);
		$accountfield = $adb->query_result($result2,0,'fieldlabel');
		$display_val .= '<td width="20%" valign="top" style="padding:0px 3px 0px 3px;">'.$accountfield.'</td>';
		
		$sql3="select fieldlabel from field where fieldid =".$contactid;
		$result3 = $adb->query($sql3);
		$contactfield = $adb->query_result($result3,0,'fieldlabel');
		$display_val .= '<td width="20%" valign="top" style="padding:0px 3px 0px 3px;">'.$contactfield.'</td>';
		
		$sql4="select fieldlabel from field where fieldid =".$potentialid;
		$result4 = $adb->query($sql4);
		$potentialfield = $adb->query_result($result4,0,'fieldlabel');
		$display_val .= '<td width="20%" valign="top" style="padding:0px 3px 0px 3px;">'.$potentialfield.'</td>';
		if($accountfield !=''&& $contactfield !='' && $potentialfield!='')
		{	
			$display_val .= '<td width="10%" valign="top" style="padding:0px 3px 0px 3px;"><a href="javascript:confirmdelete(\'index.php?action=DeleteLeadCustomFieldMapping&module=Settings&id='.$cfmid.'&return_module=Settings&return_action=ListCustomFieldMapping\')">delete</a></td></tr>';
		}else
		{
			$display_val .='<td width="10%" valign="top" style="padding:0px 3px 0px 3px;"> </td></tr>';
		}
		
	}
	return $display_val;
}

$display_fields = getListLeadMapping($image_path);

	if (isset($display_fields))
 	       $xtpl->assign("CUSTOMFIELDMAPPING",$display_fields);

$xtpl->parse("main");
$xtpl->out("main");

?>
