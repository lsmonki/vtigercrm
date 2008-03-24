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
require_once('include/database/PearDatabase.php');
require_once('include/CustomFieldUtil.php');

global $mod_strings,$app_strings,$theme;
$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD",$mod_strings);
$smarty->assign("APP",$app_strings);
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty->assign("IMAGE_PATH", $image_path);
$module_array=getCustomFieldSupportedModules();

$cfimagecombo = Array($image_path."text.gif",
$image_path."number.gif",
$image_path."percent.gif",
$image_path."currency.gif",
$image_path."date.gif",
$image_path."email.gif",
$image_path."phone.gif",
$image_path."picklist.gif",
$image_path."url.gif",
$image_path."checkbox.gif",
$image_path."text.gif",
$image_path."picklist.gif");

$cftextcombo = Array($mod_strings['Text'],
$mod_strings['Number'],
$mod_strings['Percent'],
$mod_strings['Currency'],
$mod_strings['Date'],
$mod_strings['Email'],
$mod_strings['Phone'],
$mod_strings['PickList'],
$mod_strings['LBL_URL'],
$mod_strings['LBL_CHECK_BOX'],
$mod_strings['LBL_TEXT_AREA'],
$mod_strings['LBL_MULTISELECT_COMBO']
);


$smarty->assign("MODULES",$module_array);
$smarty->assign("CFTEXTCOMBO",$cftextcombo);
$smarty->assign("CFIMAGECOMBO",$cfimagecombo);
if($_REQUEST['fld_module'] !='')
	$fld_module = $_REQUEST['fld_module'];
else
	$fld_module = 'Leads';
$smarty->assign("MODULE",$fld_module);
$smarty->assign("CFENTRIES",getCFListEntries($fld_module));
if(isset($_REQUEST["duplicate"]) && $_REQUEST["duplicate"] == "yes")
{
	$error='Custom Field in the Name '.$_REQUEST["fldlabel"].' already exists. Please specify a different Label';
	$smarty->assign("DUPLICATE_ERROR", $error);
}

if($_REQUEST['mode'] !='')
	$mode = $_REQUEST['mode'];
$smarty->assign("MODE", $mode);

if($_REQUEST['ajax'] != 'true')
	$smarty->display('CustomFieldList.tpl');	
else
	$smarty->display('CustomFieldEntries.tpl');

	/**
	* Function to get customfield entries
	* @param string $module - Module name
	* return array  $cflist - customfield entries
	*/
function getCFListEntries($module)
{
	$tabid = getTabid($module);
	global $adb,$app_strings,$theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";
	$dbQuery = "select fieldid,columnname,fieldlabel,uitype,displaytype,vtiger_convertleadmapping.cfmid from vtiger_field left join vtiger_convertleadmapping on  vtiger_convertleadmapping.leadfid = vtiger_field.fieldid where tabid=".$tabid." and generatedtype=2 order by sequence";
	$result = $adb->query($dbQuery);
	$row = $adb->fetch_array($result);
	$count=1;
	$cflist=Array();
	if($row!='')
	{
		do
		{
			$cf_element=Array();
			$cf_element['no']=$count;
			$cf_element['label']=$row["fieldlabel"];
			$fld_type_name = getCustomFieldTypeName($row["uitype"]);
			$cf_element['type']=$fld_type_name;
			if($module == 'Leads')
			{
				$mapping_details = getListLeadMapping($row["cfmid"]);
				$cf_element[]= $mapping_details['accountlabel'];
				$cf_element[]= $mapping_details['contactlabel'];
				$cf_element[]= $mapping_details['potentiallabel'];
			}
			$cf_element['tool']='<img src="'.$image_path.'editfield.gif" border="0" style="cursor:pointer;" onClick="fnvshobj(this,\'createcf\');getCreateCustomFieldForm(\''.$module.'\',\''.$row["fieldid"].'\',\''.$tabid.'\',\''.$row["uitype"].'\')" alt="'.$app_strings['LBL_EDIT_BUTTON_LABEL'].'" title="'.$app_strings['LBL_EDIT_BUTTON_LABEL'].'"/>&nbsp;|&nbsp;<img style="cursor:pointer;" onClick="deleteCustomField('.$row["fieldid"].',\''.$module.'\', \''.$row["columnname"].'\', \''.$row["uitype"].'\')" src="'.$image_path.'delete.gif" border="0"  alt="'.$app_strings['LBL_DELETE_BUTTON_LABEL'].'" title="'.$app_strings['LBL_DELETE_BUTTON_LABEL'].'"/></a>';

			$cflist[] = $cf_element;
			$count++;
		}while($row = $adb->fetch_array($result));
	}
	return $cflist;
}

/**
* Function to Lead customfield Mapping entries
* @param integer  $cfid   - Lead customfield id
* return array    $label  - customfield mapping
*/
function getListLeadMapping($cfid)
{
	global $adb;
	$sql="select * from vtiger_convertleadmapping where cfmid =".$cfid;
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i =0;$i <$noofrows;$i++)
	{
		$leadid = $adb->query_result($result,$i,'leadfid');
		$accountid = $adb->query_result($result,$i,'accountfid');
		$contactid = $adb->query_result($result,$i,'contactfid');
		$potentialid = $adb->query_result($result,$i,'potentialfid');
		$cfmid = $adb->query_result($result,$i,'cfmid');

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
	}
	return $label;
}

/* function to get the modules supports Custom Fields
*/

function getCustomFieldSupportedModules()
{
	global $adb;
	$sql="select distinct vtiger_field.tabid,name from vtiger_field inner join vtiger_tab on vtiger_field.tabid=vtiger_tab.tabid where vtiger_field.tabid not in(9,10,16,15,8,29)";
	$result = $adb->query($sql);
	while($moduleinfo=$adb->fetch_array($result))
	{
		$modulelist[$moduleinfo['name']] = $moduleinfo['name'];
	}
	return $modulelist;
}
?>
