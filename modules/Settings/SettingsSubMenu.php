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
require_once('include/CustomFieldUtil.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("IMAGE_PATH", $image_path);
$module_array=Array('Leads'=>'Leads',
			'Accounts'=>'Accounts',
			'Contacts'=>'Contacts',
			'Potentials'=>'Potentials',
			'HelpDesk'=>'HelpDesk',
			'Products'=>'Products',
			'Vendors'=>'Vendors',
			'PriceBooks'=>'PriceBooks',
			'PurchaseOrder'=>'PurchaseOrder',
			'SalesOrder'=>'SalesOrder',
			'Quotes'=>'Quotes',
			'Invoice'=>'Invoice',
			'Campaigns'=>'Campaigns'
			);
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

if($_REQUEST['type']=='CustomField')
{
	$smarty->assign("MODULES",$module_array);
	$smarty->assign("CFTEXTCOMBO",$cftextcombo);
	$smarty->assign("CFIMAGECOMBO",$cfimagecombo);
	if($_REQUEST['fld_module'] !='')
		$module = $_REQUEST['fld_module'];
	else
		$module = 'Leads';
	$smarty->assign("MODULE",$module);
	$smarty->assign("CFENTRIES",getCFListEntries($module));
	$smarty->display("CustomFieldList.tpl");
}
elseif($_REQUEST['type']=='PickList')
	$smarty->display("PickListindex.tpl");
elseif($_REQUEST['type']=='FieldOrder')
	$smarty->display("FieldOrderindex.tpl");

	function getCFListEntries($module)
	{
		$tabid = getTabid($module);
		global $adb;
		global $theme;
		$theme_path="themes/".$theme."/";
		$image_path=$theme_path."images/";
		$dbQuery = "select vtiger_fieldid,columnname,fieldlabel,uitype,displaytype from vtiger_field where vtiger_tabid=".$tabid." and generatedtype=2 order by sequence";
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
				$cf_element['tool']='<img src="'.$image_path.'editfield.gif" border="0" onClick="getCreateCustomFieldForm(\''.$module.'\',\''.$row["fieldid"].'\',\''.$tabid.'\',\''.$row["uitype"].'\')" alt="Edit" title="Edit"/>&nbsp;|&nbsp;<a href="javascript:deleteCustomField('.$row["fieldid"].',\''.$module.'\', \''.$row["columnname"].'\', \''.$row["uitype"].'\')"><img src="'.$image_path.'delete.gif" border="0"  alt="Delete" title="Delete"/></a>';

				$cflist[] = $cf_element;
				$count++;
			}while($row = $adb->fetch_array($result));
		}
		return $cflist;
	}

?>
