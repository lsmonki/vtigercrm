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
			'Vendor'=>'Vendor',
			'PriceBook'=>'PriceBook',
			'PurchaseOrder'=>'PurchaseOrder',
			'SalesOrder'=>'SalesOrder',
			'Quotes'=>'Quotes',
			'Invoice'=>'Invoice'
			);
if($_REQUEST['type']=='CustomField')
{
	$smarty->assign("MODULES",$module_array);
	$module = 'Leads';
	$smarty->assign("MODULE",$module);
	$smarty->assign("CFENTRIES",getCFListEntries($module));
	//$smarty->display("CustomFieldindex.tpl");
	$smarty->display("CustomFieldList.tpl");
}
	$smarty->display("CustomFieldindex.tpl");
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
	$dbQuery = "select fieldid,columnname,fieldlabel,uitype,displaytype from field where tabid=".$tabid." and generatedtype=2 order by sequence";
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
			$cf_element['tool']='<a href="index.php?module=Settings&action=CreateCustomField&fieldid='.$row["fieldid"].'&tabid='.$tabid.'&uitype='.$row["uitype"].'&fld_module='.$fld_module.'&parenttab=Settings" ><img src="'.$image_path.'editfield.gif" border="0" alt="Edit" title="Edit"/></a>&nbsp;|&nbsp;<a rhef="javascript:deleteCustomField('.$row["fieldid"].',\''.$fld_module.'\', \''.$row["columnname"].'\', \''.$row["uitype"].'\')"><img src="'.$image_path.'delete.gif" border="0"  alt="Delete" title="Delete"/></a>';

			$cflist[] = $cf_element;
			$count++;
		}while($row = $adb->fetch_array($result));
	}
	return $cflist;
}
	
?>
