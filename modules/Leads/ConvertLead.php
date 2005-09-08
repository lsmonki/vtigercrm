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
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('include/database/PearDatabase.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

global $vtlog;
if(isset($_REQUEST['record'])) {
    $id = $_REQUEST['record'];
$vtlog->logthis(" the id is ".$id,'debug');  
}
//Retreive lead details from database

$userid = $row["smownerid"];

$vtlog->logthis(" the userid is ".$userid,'debug');  
$crmid = $adb->getUniqueID("crmentity");
//$sql_crmentity = "insert into crmentity(crmid,smcreatorid,smownerid,setype,presence,deleted) values(".$id.",".$userid.",".$userid.",account,0,0)";

//$adb->query($sql_crmentity);


$sql = "SELECT firstname, lastname, company, smownerid from leaddetails inner join crmentity on crmentity.crmid=leaddetails.leadid where leaddetails.leadid =".$id;
$result = $adb->query($sql);
$row = $adb->fetch_array($result);

$firstname = $row["firstname"];
$vtlog->logthis(" the firstname is ".$firstname,'debug');  
$lastname = $row["lastname"];
$vtlog->logthis(" the lastname is ".$lastname,'debug');  
$company = $row["company"];
$vtlog->logthis(" the company is  ".$company,'debug');  
$potentialname = $row["company"] ."-";

$vtlog->logthis(" the potentialname is ".$potentialname,'debug');  

//Retreiving the current user id
global $current_user;
$modified_user_id = $current_user->id;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Convert Lead view");

$xtpl=new XTemplate ('modules/Leads/ConvertLead.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);

$xtpl->assign("CALENDAR_LANG", $app_strings['LBL_JSCALENDAR_LANG']);
$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));
$xtpl->assign("DATEFORMAT", $current_user->date_format);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("FIRST_NAME",$firstname);
$xtpl->assign("LAST_NAME",$lastname);
$xtpl->assign("ID", $id);
$xtpl->assign("CURRENT_USER_ID", $modified_user_id);
$xtpl->assign("RETURN_ACTION","DetailView");
$xtpl->assign("RETURN_MODULE","Leads");
$xtpl->assign("RETURN_ID",$id);
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(), $userid));
$xtpl->assign("ACCOUNT_NAME",$company);
$xtpl->assign("CREATE_POTENTIAL","yes");
$xtpl->assign("POTENTIAL_NAME", $potentialname);
$xtpl->assign("DATE_CLOSED", $focus->closedate);
$xtpl->assign("POTENTIAL_AMOUNT", $potential_amount);

$sales_stage_query="select * from sales_stage";
$sales_stage_result = $adb->query($sales_stage_query);
$noofsalesRows = $adb->num_rows($sales_stage_result);
$sales_stage_fld = '';
for($j = 0; $j < $noofsalesRows; $j++)
{
	
	$sales_stageValue=$adb->query_result($sales_stage_result,$j,strtolower(sales_stage));

	if($value == $sales_stageValue)
	{
		$chk_val = "selected";
	}
	else
	{
		$chk_val = '';
	}

	$sales_stage_fld.= '<OPTION value="'.$sales_stageValue.'" '.$chk_val.'>'.$sales_stageValue.'</OPTION>';
}
$sales_stage_fld .= '</td>';
$xtpl->assign("POTENTIAL_SALES_STAGE", $sales_stage_fld);

$xtpl->parse("main");
$xtpl->out("main");
?>
