<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header$
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Leads/Lead.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
#require_once('include/listview.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                      ,'leadstatus'=>'leadstatus_dom'
                      ,'rating'=>'rating_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$seedLead = new Lead();

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['company'])) $company = $_REQUEST['company'];
	if (isset($_REQUEST['leadsource'])) $leadsource = $_REQUEST['leadsource'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['mobile'])) $mobile = $_REQUEST['mobile'];
	if (isset($_REQUEST['lead_status'])) $leadstatus = $_REQUEST['lead_status'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	
	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field  where tablename='leadscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
                $str=" leadscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
        }
}
//upto this added for Custom Field


	if(isset($lastname) && $lastname != "") array_push($where_clauses, "leaddetails.lastname like '$lastname%'");
	if(isset($firstname) && $firstname != "")	array_push($where_clauses, "leaddetails.firstname like '$firstname%'");
	if(isset($company) && $company != "")	array_push($where_clauses, "leaddetails.company like '$company%'");
	if(isset($leadsource) && $leadsource != "") array_push($where_clauses, "leaddetails.leadsource = '$leadsource'");
	if(isset($industry) && $industry != "") array_push($where_clauses, "leaddetails.industry = '$industry'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "leadaddress.phone like '%$phone%'");
	if(isset($email) && $email != "") array_push($where_clauses, "leaddetails.email like '$email%'");
	if(isset($mobile) && $mobile != "") array_push($where_clauses, "leadaddressmobile like '%$mobile%'");
	if(isset($leadstatus) && $leadstatus != "") array_push($where_clauses, "leaddetails.leadstatus =  '$leadstatus'");
	if(isset($rating) && $rating != "") array_push($where_clauses, "leaddetails.rating = '$rating'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "leadaddress.lane like '$address_street%'");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "leadaddress.city like '$address_city%'");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "leadaddress.state like '$address_state%'");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "leadaddress.code like '$address_postalcode%'");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "leadaddress.country like '$address_country%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
	if(isset($assigned_user_id) && $assigned_user_id != "") array_push($where_clauses, "crmentity.smownerid = '$assigned_user_id'");
	
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Leads/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($company)) $search_form->assign("COMPANY", $_REQUEST['company']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
		$advsearch = 'true';
		//if(isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		//if(isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		//if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		//if(isset($do_not_call)) $search_form->assign("DO_NOT_CALL", $do_not_call);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($mobile)) $search_form->assign("MOBILE", $mobile);
		if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		if (isset($leadsource)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], $leadsource, $advsearch));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], '', $advsearch));

		if (isset($leadstatus)) $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($comboFieldArray['leadstatus_dom'], $leadstatus, $advsearch));
		else $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($comboFieldArray['leadstatus_dom'], '', $advsearch));

		if (isset($rating)) $search_form->assign("RATING_OPTIONS", get_select_options($comboFieldArray['rating_dom'], $rating, $advsearch));
		else $search_form->assign("RATING_OPTIONS", get_select_options($comboFieldArray['rating_dom'], '', $advsearch));

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], $industry, $advsearch));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], '', $advsearch));			
		if (isset($assigned_user_id)) $search_form->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $assigned_user_id), $assigned_user_id));
		else $search_form->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active",$assigned_user_id),$assigned_user_id));
//Added for Custom Field Search
$sql="select * from field  where tablename='leadscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "leadscf", "leadcf", "leadid", $app_strings, $theme,$column,$fieldlabel);
$search_form->assign("CUSTOMFIELD", $custfld);
//upto this added for Custom Field


		$search_form->parse("advanced");
		$search_form->out("advanced");
	}
	else {
		$search_form->parse("main");
		$search_form->out("main");
	}
	echo get_form_footer();
	echo "\n<BR>\n";
}


$focus = new Lead();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Leads/ListView.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
$query = getListQuery("Leads");

if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}

if(isset($_REQUEST['viewname']))
{
   if($_REQUEST['viewname'] == 'All')
   {
        $defaultcv_criteria = '';
   }
   else
   {
  	$defaultcv_criteria = $_REQUEST['viewname'];
   }
  $query .= " and leadstatus like "."'%" .$defaultcv_criteria ."%'";
	$viewname = $_REQUEST['viewname'];
  	$view_script = "<script language='javascript'>
		function set_selected()
		{
			len=document.massdelete.view.length;
			for(i=0;i<len;i++)
			{
				if(document.massdelete.view[i].value == '$viewname')
					document.massdelete.view[i].selected = true;
			}
		}
		set_selected();
		</script>";

}

$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
	$query .= ' ORDER BY '.$order_by;
	$url_qry .="&order_by=".$order_by;
}

$list_result = $adb->query($query);

//Append URL Strings 

//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);
//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
        $start = $_REQUEST['start'];
}
else
{

        $start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);

//Retreive the List View Table Header

$listview_header = getListViewHeader($focus,"Leads");
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getListViewEntries($focus,"Leads",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);
$xtpl->assign("SELECT_SCRIPT", $view_script);

if(isset($navigation_array['start']))
{
        $startoutput = '<a href="index.php?action=index&module=Leads'.$url_qry.'&start=1"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=index&module=Leads'.$url_qry.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=index&module=Leads'.$url_qry.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=index&module=Leads'.$url_qry.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
}
else
{
        $prevoutput = '[ Prev ]';
}
$xtpl->assign("Start", $startoutput);
$xtpl->assign("End", $endoutput);
$xtpl->assign("Next", $nextoutput);
$xtpl->assign("Prev", $prevoutput);

$xtpl->parse("main");
$xtpl->out("main");

?>
