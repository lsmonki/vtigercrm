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
global $mod_strings;
global $app_strings;
global $app_list_strings;
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH", $image_path);

$smarty->assign("RETURN_MODULE","Settings");
$smarty->assign("RETURN_ACTION","");

function getAccountCustomValues($leadid,$accountid)
{
	global $adb;
	$accountcf=Array();
	$sql="select fieldid,fieldlabel from vtiger_field,vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype=2 and vtiger_tab.name='Accounts'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	
	for($i=0;$i<$noofrows;$i++)
	{
        	$account_field['fieldid']=$adb->query_result($result,$i,"fieldid");
	        $account_field['fieldlabel']=$adb->query_result($result,$i,"fieldlabel");

		if($account_field['fieldid']==$accountid)
			$account_field['selected'] = "selected";
		else
			$account_field['selected'] = "";
		$account_cfelement[]=$account_field;
	}
	$accountcf[$leadid.'_account']=$account_cfelement;
	return $accountcf;
}

function getContactCustomValues($leadid,$contactid)
{	
	global $adb;	
	$contactcf=Array();
	$sql="select fieldid,fieldlabel from vtiger_field,vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype=2 and vtiger_tab.name='Contacts'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$contact_field['fieldid']=$adb->query_result($result,$i,"fieldid");
		$contact_field['fieldlabel']=$adb->query_result($result,$i,"fieldlabel");
	
                if($contact_field['fieldid']==$contactid)
                        $contact_field['selected']="selected";
		else
                        $contact_field['selected'] = "";
		$contact_cfelement[]=$contact_field;
	}
	$contactcf[$leadid.'_contact'] = $contact_cfelement;
        return $contactcf;
}	

function getPotentialCustomValues($leadid,$potentialid)
{
	global $adb;	
	$potentialcf=Array();
	$sql="select fieldid,fieldlabel from vtiger_field,vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype=2 and vtiger_tab.name='Potentials'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	for($i=0; $i<$noofrows; $i++)
	{
		$potential_field['fieldid']=$adb->query_result($result,$i,"fieldid");
		$potential_field['fieldlabel']=$adb->query_result($result,$i,"fieldlabel");

		if($potential_field['fieldid']==$potentialid)
			 $potential_field['selected']="selected";
		else
                         $potential_field['selected'] = "";
		$potential_cfelement[]=$potential_field;
	}
	$potentialcf[$leadid.'_potential']=$potential_cfelement;
        return $potentialcf;
}
$lead_sql="select vtiger_fieldid,fieldlabel from vtiger_field,vtiger_tab where vtiger_field.tabid=vtiger_tab.tabid and generatedtype=2 and vtiger_tab.name='Leads'";
$result = $adb->query($lead_sql);
$noofrows = $adb->num_rows($result);

$display_val="<table border=0 cellspacing=1 cellpadding=2 width=75%>";
$leadcf=Array();
for($i=0; $i<$noofrows; $i++)
{
	$lead_field['fieldid']=$adb->query_result($result,$i,"fieldid");
	$lead_field['fieldlabel']=$adb->query_result($result,$i,"fieldlabel");
	$convert_sql="select * from vtiger_convertleadmapping where leadfid=".$lead_field['fieldid'];
	$convert_result = $adb->query($convert_sql);

	$no_rows = $adb->num_rows($convert_result);
	for($j=0; $j<$no_rows; $j++)
	{
		$accountid=$adb->query_result($convert_result,$j,"accountfid");
		$contactid=$adb->query_result($convert_result,$j,"contactfid");
		$potentialid=$adb->query_result($convert_result,$j,"potentialfid");
	
		
	}
	$lead_field['account']=getAccountCustomValues($lead_field['fieldid'],$accountid);
	$lead_field['contact']=getContactCustomValues($lead_field['fieldid'],$contactid);
	$lead_field['potential']=getPotentialCustomValues($lead_field['fieldid'],$potentialid);
	$leadcf[$lead_field['fieldlabel']]= $lead_field;
}
$smarty->assign("CUSTOMFIELDMAPPING",$leadcf);

$smarty->display("CustomFieldMapping.tpl");

?>
