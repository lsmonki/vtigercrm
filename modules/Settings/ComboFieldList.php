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
require_once('database/DatabaseConnection.php');
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title("Settings",$mod_strings['LBL_MODULE_NAME'].": ".$mod_strings[$_REQUEST['fld_module']].$mod_strings['PicklistFields'], true);
echo '<BR>';
//echo get_form_header("Standard Fields", "", false );


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$fld_module = $_REQUEST["fld_module"];
//Retreiving the custom picklist field array
$usrFldArray = getUserFldArray($fld_module);

$xtpl=new XTemplate ('modules/Settings/ComboFieldList.html');
if($fld_module == 'Leads')
{
	$custFldArray = Array
		     ('Lead Source'=>'leadsource'
                      ,'Salutation'=>'salutation'
                      ,'Lead Status'=>'leadstatus'
                      ,'Industry'=>'industry'
                      ,'Rating'=>'rating'
                      ,'License Key'=>'licencekeystatus');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
		 
}
elseif($fld_module == 'Accounts')
{
	$custFldArray =	Array('Account Type'=>'accounttype'
                      ,'Industry'=>'industry');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Contacts')
{
	$custFldArray = Array('Type'=>'usertype'
                      ,'Salutation'=>'salutationtype');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Potentials')
{
	$custFldArray = Array('Lead Source'=>'leadsource'
                      ,'Business Type'=>'businesstype'
                      ,'Sales Stage'=>'sales_stage');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}


 
function fetchTabIDVal($fldmodule)
{

  global $adb;
  $query = "select tabid from tab where tablabel='" .$fldmodule ."'";
  $tabidresult = $adb->query($query);
  return $adb->query_result($tabidresult,0,"tabid");
}

$tabid = fetchTabIDVal($fldmodule);





//Standard PickList Fields
function getStdOutput($custFldArray, $mod_strings)
{
	echo get_form_header("Standard Fields", "", false );
	$standCustFld= ''; 
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10">'.$mod_strings['FieldName'].'</td>';
	$standCustFld .=  '<td width="15%" class="moduleListTitle"></td></tr>';
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
		$standCustFld .= '<td width="33%" height="21"><p style="margin-left: 10"><a href="index.php?module=Settings&action=EditComboField&fld_module='.$fld_module.'&fld_name='.$custFldName.'&table_name='.$custFldTableName.'&column_name='.$custFldTableName.'">'.$mod_strings['Edit'].'</a></td></tr>';
		$i++; 
	}
	$standCustFld .='</table>';
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARD_COMBO_FIELDS", $standCustFld);

//Custom PickList Fields
$userCustFld ='';
$i=1;
foreach($usrFldArray as $custFldName => $custFldColName)
{
	if ($i%2==0)
	{
		$trowclass = 'evenListRow';
	}
	else
	{	
		$trowclass = 'oddListRow';
	}
	$custFldTableName = $fld_module.'_'.$custFldColName;
	$userCustFld .= '<tr class="'.$trowclass.'">';
	$userCustFld .= '<td width="34%" height="21"><p style="margin-left: 10;">'.$custFldName.'</td>';
	$userCustFld .= '<td width="33%" height="21"><p style="margin-left: 10"><a href="index.php?module=Settings&action=EditComboField&fld_module='.$fld_module.'&fld_name='.$custFldName.'&table_name='.$custFldColName.'&column_name='.$custFldColName.'">'.$mod_strings['Edit'].'</a></td></tr>';
	$i++; 
}
$cust_fld_header = get_form_header("Custom Fields", "", false );
$xtpl->assign("CUSTOMHEADER", $cust_fld_header);
$xtpl->assign("CUSTOM_COMBO_FIELDS", $userCustFld);

$xtpl->parse("main");
$xtpl->out("main");

function getUserFldArray($fld_module)
{
	$user_fld = Array();
	$query = "select * from field where generatedtype=2 and tabid=".fetchTabIDVal($fld_module)." and uitype IN (15,16)";
//        echo $query;
        $result = mysql_query($query);
	$noofrows = mysql_num_rows($result);
        if($noofrows > 0)
        {
          for($i=0; $i<$noofrows; $i++)
          {
            $user_fld[mysql_result($result,$i,"fieldlabel")] = mysql_result($result,$i,"columnname");	
          }
        }
          return $user_fld;
}
?>
