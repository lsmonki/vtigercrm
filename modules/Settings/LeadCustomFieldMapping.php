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

	
require_once('XTemplate/xtpl.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_LEAD_MAP_CUSTOM_FIELD'], true);
echo '<br><br>';
echo $mod_strings['leadCustomFieldDescription'];
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate('modules/Settings/LeadCustomFieldMapping.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","");

function getAccountCustomValues($leadid,$accountid)
{
	global $adb;

	$sql="select fieldid,fieldlabel from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Accounts'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);
	
	$combo="<select name='".$leadid."_account'>
                 <option value='None'>-None-</option>" ;
	
	for($i=0;$i<$noofrows;$i++)
	{
        	$account_field_id=$adb->query_result($result,$i,"fieldid");
	        $account_field_label=$adb->query_result($result,$i,"fieldlabel");

		$combo.="<option value='".$account_field_id."'";
		if($account_field_id==$accountid)
		{
			$combo.=" selected";
		}
		$combo.=">".$account_field_label."</option>";
	
	}
	$combo.="</select>";
	
	return $combo;
}

function getContactCustomValues($leadid,$contactid)
{	
	global $adb;	

	$sql="select fieldid,fieldlabel from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Contacts'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);

	 $combo="<select name='".$leadid."_contact'>                                                                            <option value='None'>-None-</option>" ;

	for($i=0; $i<$noofrows; $i++)
	{
		$contact_field_id=$adb->query_result($result,$i,"fieldid");
		$contact_field_label=$adb->query_result($result,$i,"fieldlabel");
	
		$combo.="<option value='".$contact_field_id."'";
                if($contact_field_id==$contactid)
                        $combo.=" selected";

                $combo.=">".$contact_field_label."</option>";

	}
	$combo.="</select>";
        return $combo;
}	

function getPotentialCustomValues($leadid,$potentialid)
{
	global $adb;	

	$sql="select fieldid,fieldlabel from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Potentials'";
	$result = $adb->query($sql);
	$noofrows = $adb->num_rows($result);

	$combo="<select name='".$leadid."_potential'>                                                                            <option value='None'>-None-</option>" ;	
	for($i=0; $i<$noofrows; $i++)
	{
		$potential_field_id=$adb->query_result($result,$i,"fieldid");
		$potential_field_label=$adb->query_result($result,$i,"fieldlabel");
	
		$combo.="<option value='".$potential_field_id."'";
		if($potential_field_id==$potentialid)
			$combo.=" selected";
		$combo.=">".$potential_field_label."</option>";
	}
	$combo.="</select>";
        return $combo;
}
$lead_sql="select fieldid,fieldlabel from field,tab where field.tabid=tab.tabid and generatedtype=2 and tab.name='Leads'";
$result = $adb->query($lead_sql);
$noofrows = $adb->num_rows($result);

$display_val="<table border=0 cellspacing=1 cellpadding=2 width=75%>";

for($i=0; $i<$noofrows; $i++)
{
	$lead_field_id=$adb->query_result($result,$i,"fieldid");
	$lead_field_label=$adb->query_result($result,$i,"fieldlabel");
	$display_val.="<tr><td nowrap class='customdataLabel' width=\"10%\">".$lead_field_label;

	$convert_sql="select * from convertleadmapping where leadfid=".$lead_field_id;
	$convert_result = $adb->query($convert_sql);

	$no_rows = $adb->num_rows($convert_result);
	for($j=0; $j<$no_rows; $j++)
	{
		$accountid=$adb->query_result($convert_result,$j,"accountfid");
		$contactid=$adb->query_result($convert_result,$j,"contactfid");
		$potentialid=$adb->query_result($convert_result,$j,"potentialfid");
	
		
	}
		$account_combo=getAccountCustomValues($lead_field_id,$accountid);
		$contact_combo=getContactCustomValues($lead_field_id,$contactid);
		$potential_combo=getPotentialCustomValues($lead_field_id,$potentialid);
		$display_val.="</td>";
		$display_val.="<td class=\"customdataLabel\" >".$account_combo."</td>";
		$display_val.="<td class=\"customdataLabel\" >".$contact_combo."</td>";
		$display_val.="<td class=\"customdataLabel\">".$potential_combo."</td>";
		$display_val.="</tr>";

}
	 $display_val.="<table>";
	if (isset($display_val))
 	       $xtpl->assign("CUSTOMFIELDMAPPING",$display_val);

$xtpl->parse("main");
$xtpl->out("main");

?>
