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


require_once('database/DatabaseConnection.php');
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title("Settings", "Settings: ".$_REQUEST['fld_module']." Picklist Fields", true);
echo '<br>';

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$fld_module = $_REQUEST["fld_module"];
$xtpl=new XTemplate ('modules/Settings/ComboFieldList.html');
if($fld_module == 'Leads')
{
	$custFldArray = Array
		     ('Lead Source'=>'lead_source'
                      ,'Salutation'=>'salutation'
                      ,'Lead Status'=>'lead_status'
                      ,'Industry'=>'industry'
                      ,'Rating'=>'rating'
                      ,'License Key'=>'license_key');
		 
}
elseif($fld_module == 'Accounts')
{
	$custFldArray =	Array('Type'=>'account_type'
                      ,'Industry'=>'industry');
}
elseif($fld_module == 'Contacts')
{
	$custFldArray = Array('Lead Source'=>'lead_source'
                      ,'Salutation'=>'salutation');
}
elseif($fld_module == 'Opportunities')
{
	$custFldArray = Array('Lead Source'=>'lead_source'
                      ,'Type'=>'opportunity_type'
                      ,'Sales Stage'=>'sales_stage');
}
$standCustFld='';
$i=1;
foreach($custFldArray as $custFldName => $custFldTableName)
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
	$standCustFld .= '<td width="34%" height="21"><p style="margin-left: 10;">'.$custFldName.'</td>';
	$standCustFld .= '<td width="33%" height="21"><p style="margin-left: 10"><a href="index.php?module=Settings&action=EditComboField&fld_module='.$fld_module.'&fld_name='.$custFldName.'&table_name='.$custFldTableName.'">Edit</a></td></tr>';
	$i++; 
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARD_CUSTOM_FIELDS", $standCustFld);

$xtpl->parse("main");
$xtpl->out("main");
?>
