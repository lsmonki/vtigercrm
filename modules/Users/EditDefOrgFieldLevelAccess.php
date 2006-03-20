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


require_once('include/database/PearDatabase.php');
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/utils/utils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$field_module = Array('Leads','Accounts','Contacts','Potentials','HelpDesk','Products','Notes','Emails','Activities','Events','Vendors','PriceBooks','Quotes','PurchaseOrder','SalesOrder','Invoice');
$allfields=Array();
foreach($field_module as $fld_module)
{
	$fieldListResult = getDefOrgFieldList($fld_module);
	$noofrows = $adb->num_rows($fieldListResult);
	$allfields[$fld_module] = getStdOutput($fieldListResult, $noofrows, $mod_strings,$profileid);
}
//Standard PickList Fields
function getStdOutput($fieldListResult, $noofrows, $mod_strings,$profileid)
{
	global $adb;
	$standCustFld = Array();
	for($i=0; $i<$noofrows; $i++,$row++)
	{
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
                $mandatory = '';
		$readonly = '';
                if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16)
                {
                        $mandatory = '<font color="red">*</font>';
						$readonly = 'disabled';
                }

		$standCustFld []= $mandatory.' '.$adb->query_result($fieldListResult,$i,"fieldlabel");
		if($adb->query_result($fieldListResult,$i,"visible") == 0)
		{
			$visible = "checked";
		}
		else
		{
			$visible = "";
		}	
		$standCustFld []= '<input type="checkbox" name="'.$adb->query_result($fieldListResult,$i,"fieldid").'" '.$visible.' '.$readonly.'>';
		
	}
	$standCustFld=array_chunk($standCustFld,2);	
	$standCustFld=array_chunk($standCustFld,2);	
	return $standCustFld;
}

$smarty->assign("FIELD_INFO",$field_module);
$smarty->assign("FIELD_LISTS",$allfields);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("MODE",'edit');                    
$smarty->display("FieldAccess.tpl");

?>
