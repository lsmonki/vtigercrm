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
require_once('modules/Users/UserInfoUtil.php');
require_once('include/utils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title("Users", $_REQUEST['fld_module'].'Field Level Access', true);
echo '<BR>';
//echo get_form_header("Standard Fields", "", false );

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$fld_module = $_REQUEST["fld_module"];
//Retreiving the fields array

$xtpl=new XTemplate ('modules/Users/ListFieldPermissions.html');

$profileid = $_REQUEST["profileid"];

$fieldListResult = getProfile2FieldList($fld_module, $profileid);
$noofrows = $adb->num_rows($fieldListResult);
$standCustFld = getStdOutput($fieldListResult, $noofrows, $mod_strings,$profileid);

//Standard PickList Fields
function getStdOutput($fieldListResult, $noofrows, $mod_strings,$profileid)
{
	global $adb;
	$standCustFld= '';
	$standCustFld .= '<BR>';
	$standCustFld .= '<table width="25%" cellpadding="2" cellspacing="0" border="0">';
	$standCustFld .= '<form action="index.php" method="post" name="new" id="form">';
	$standCustFld .= '<input type="hidden" name="fld_module" value="'.$_REQUEST['fld_module'].'">';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="profileid" value="'.$profileid.'">';
	$standCustFld .= '<input type="hidden" name="action" value="EditFieldLevelAccess">';
	$standCustFld .= '<tr><br>';
	$standCustFld .= '<td><input title="Edit" accessKey="C" class="button" type="submit" name="Edit" value="Edit"></td>';
	$standCustFld .= '</tr></form></table>';
	$standCustFld .= '<BR>';
	$standCustFld .=  get_form_header("Standard Fields", "", false );
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10">Field Name</td>';
	$standCustFld .=  '<td width="33%" class="moduleListTitle">Visible</td>';
	$standCustFld .=  '</tr>';
	
	for($i=0; $i<$noofrows; $i++)
	{
		if ($i%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		
		$uitype = $adb->query_result($fieldListResult,$i,"uitype");
		$mandatory = '';
		if($uitype == 2 || $uitype == 51 || $uitype == 6 || $uitype == 22)	
		{
			$mandatory = '<font color="red">*</font>';
		}

		$standCustFld .= '<td width="34%" height="21"><p style="margin-left: 10;">'.$mandatory.' '.$adb->query_result($fieldListResult,$i,"fieldlabel").'</td>';
		if($adb->query_result($fieldListResult,$i,"visible") == 0)
		{
			$visible = "Yes";
		}
		else
		{
			$visible = "No";
		}	
		$standCustFld .= '<td width="33%" height="21"><p style="margin-left: 10;">'.$visible.'</td>';
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARDFIELDS", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");


?>
