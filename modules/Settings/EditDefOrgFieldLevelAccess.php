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


$field_module=getFieldModuleAccessArray();
$allfields=Array();
foreach($field_module as $fld_module)
{
	$fieldListResult = getDefOrgFieldList($fld_module);
	$noofrows = $adb->num_rows($fieldListResult);
	$language_strings = return_module_language($current_language,$fld_module);
	$allfields[$fld_module] = getStdOutput($fieldListResult, $noofrows, $language_strings,$profileid);
}
if($_REQUEST['fld_module'] != '')
	$smarty->assign("DEF_MODULE",$_REQUEST['fld_module']);
else
	$smarty->assign("DEF_MODULE",'Leads');

/** Function to get the field label/permission array to construct the default orgnization field UI for the specified profile 
  * @param $fieldListResult -- mysql query result that contains the field label and uitype:: Type array
  * @param $mod_strings -- i18n language mod strings array:: Type array
  * @param $profileid -- profile id:: Type integer
  * @returns $standCustFld -- field label/permission array :: Type varchar
  *
 */	
function getStdOutput($fieldListResult, $noofrows, $lang_strings,$profileid)
{
	global $adb;
	$standCustFld = Array();
	for($i=0; $i<$noofrows; $i++,$row++)
	{
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
		$displaytype = $adb->query_result($fieldListResult,$i,"displaytype");
		$fieldlabel = $adb->query_result($fieldListResult,$i,"fieldlabel");
                $mandatory = '';
		$readonly = '';
                if($uitype == 2 || $uitype == 3 || $uitype == 6 || $uitype == 22 || $uitype == 73 || $uitype == 24 || $uitype == 81 || $uitype == 50 || $uitype == 23 || $uitype == 16 || $uitype == 20 || $uitype == 53 || $displaytype == 3 || ($displaytype != 3 && $fieldlabel == "Activity Type" && $uitype == 15))
                {
                        $mandatory = '<font color="red">*</font>';
						$readonly = 'disabled';
                }

		if($lang_strings[$fieldlabel] !='')
			$standCustFld []= $mandatory.' '.$lang_strings[$fieldlabel];
		else
			$standCustFld []= $mandatory.' '.$fieldlabel;
		if($adb->query_result($fieldListResult,$i,"visible") == 0 && $displaytype!=3)
		{
			if($fieldlabel == 'Activity Type')
			{
				$visible = "checked";
                        	$readonly = 'disabled';
			}
			else
			$visible = "checked";
		}
		elseif($displaytype==3)
		{
			$visible = "checked";
			$readonly = 'disabled';
		}
		else
		{
			$visible = "";
		}	
		$standCustFld []= '<input type="checkbox" name="'.$adb->query_result($fieldListResult,$i,"fieldid").'" '.$visible.' '.$readonly.'>';
		
	}
	$standCustFld=array_chunk($standCustFld,2);	
	$standCustFld=array_chunk($standCustFld,4);	
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
