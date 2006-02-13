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
		     ($mod_strings['LBL_LEAD_SOURCE']=>'leadsource'
                      ,$mod_strings['LBL_SALUTATION']=>'salutationtype'
                      ,$mod_strings['LBL_LEAD_STATUS']=>'leadstatus'
                      ,$mod_strings['LBL_INDUSTRY']=>'industry'
                      ,$mod_strings['LBL_RATING']=>'rating');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
		 
}
elseif($fld_module == 'Accounts')
{
	$custFldArray =	Array($mod_strings['LBL_ACCOUNT_TYPE']=>'accounttype'
                      ,$mod_strings['LBL_INDUSTRY']=>'industry');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Contacts')
{
	$custFldArray = Array($mod_strings['LBL_SALUTATION']=>'salutationtype'
			      ,$mod_strings['LBL_LEAD_SOURCE']=>'leadsource');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Potentials')
{
	$custFldArray = Array($mod_strings['LBL_LEAD_SOURCE']=>'leadsource'
                      ,$mod_strings['LBL_BUSINESS_TYPE']=>'opportunity_type'
                      ,$mod_strings['LBL_CURRENCY_TYPE']=>'currency'
                      ,$mod_strings['LBL_SALES_STAGE']=>'sales_stage');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'HelpDesk')
{
	$custFldArray = Array($mod_strings['LBL_PRIORITY']=>'ticketpriorities'
			,$mod_strings['LBL_SEVERITY']=>'ticketseverities'
			,$mod_strings['LBL_STATUS']=>'ticketstatus'
			,$mod_strings['LBL_CATEGORY']=>'ticketcategories'
			,$mod_strings['LBL_FAQ_CATEGORY']=>'faqcategories');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Products')
{
	$custFldArray = Array($mod_strings['LBL_MANUFACTURER']=>'manufacturer'
			,$mod_strings['LBL_PRODUCT_CATEGORY']=>'productcategory'
			,$mod_strings['LBL_USAGEUNIT']=>'usageunit'
			,$mod_strings['LBL_TAXCLASS']=>'taxclass'
			,$mod_strings['LBL_GLACCT']=>'glacct'
			);
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Events')
{
	$custFldArray = Array($mod_strings['LBL_STATUS']=>'eventstatus'
			);
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Activities')
{
	$custFldArray = Array($mod_strings['LBL_STATUS']=>'taskstatus'
			,$mod_strings['LBL_PRIORITY']=>'taskpriority');
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Rss')
{
        $custFldArray = Array($mod_strings['LBL_RSS_CATEGORY']=>'rsscategory');
        $standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Vendor')
{
	$custFldArray = Array($mod_strings['LBL_GLACCT']=>'glacct'
			);
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Quotes')
{
	$custFldArray = Array($mod_strings['LBL_QUOTE_STAGE']=>'quotestage'
				,$mod_strings['LBL_CARRIER']=>'carrier'	
			);
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'Orders')
{
	$custFldArray = Array($mod_strings['LBL_CARRIER']=>'carrier'
			);
	$standCustFld = getStdOutput($custFldArray, $mod_strings);
}
elseif($fld_module == 'SalesOrder')
{
	$custFldArray = Array($mod_strings['LBL_CARRIER']=>'carrier'
			);
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
	$standCustFld = ''; 
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="40%"><tr><td>';
	$standCustFld .= get_form_header($mod_strings['LBL_STANDARD_FIELDS'], "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="40%">';
	$standCustFld .= '<tr height=20>';
	$standCustFld .= '<td class="ModuleListTitle" width="20%" style="padding:0px 3px 0px 3px;"><div><b>Operation</b></div></td>';
	$standCustFld .= '<td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['FieldName'].'</b></td>';
	$standCustFld .= '</tr>';
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
		$standCustFld .= '<td width="12%" height="21" style="padding:0px 3px 0px 3px;"><div><a href="index.php?module=Settings&action=EditComboField&fld_module='.$_REQUEST["fld_module"].'&fld_name='.$custFldName.'&table_name='.$custFldTableName.'&column_name='.$custFldTableName.'">'.$mod_strings['Edit'].'</a></div></td>';
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$custFldName.'</td>';
		$standCustFld .= '</tr>';
		$i++; 
	}
	$standCustFld .='</table>';
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARD_COMBO_FIELDS", $standCustFld);


if($fld_module != 'Activities' && $fld_module != 'Events' && $fld_module != 'Rss')
{
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
		$userCustFld .= '<td width="12%" height="21"><a href="index.php?module=Settings&action=EditComboField&fld_module='.$fld_module.'&fld_name='.$custFldName.'&table_name='.$custFldColName.'&column_name='.$custFldColName.'">'.$mod_strings['Edit'].'</a></td>';
		$userCustFld .= '<td height="21">'.$custFldName.'</td></tr>';
		$i++; 
	}
	$cust_fld_header = get_form_header($mod_strings['CustomFields'], "", false );
	$xtpl->assign("CUSTOMHEADER", $cust_fld_header);
	$custom_combo_table_header = '<tr>
		<td class="ModuleListTitle" width="20%" style="padding:0px 3px 0px 3px;"><div><b>Operation</b></div></td>
        <td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['FieldName'].'</b></td>
	</tr>';

	$xtpl->assign("CUSTOM_COMBO_HEADER",$custom_combo_table_header);
	$xtpl->assign("CUSTOM_COMBO_FIELDS", $userCustFld);
}
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
