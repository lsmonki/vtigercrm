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
require_once('include/listview.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Leads');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedLead = new Lead();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['first_name'])) $first_name = $_REQUEST['first_name'];
	if (isset($_REQUEST['last_name'])) $last_name = $_REQUEST['last_name'];
	if (isset($_REQUEST['company'])) $company = $_REQUEST['company'];
	if (isset($_REQUEST['lead_source'])) $lead_source = $_REQUEST['lead_source'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['mobile'])) $mobile = $_REQUEST['mobile'];
	if (isset($_REQUEST['lead_status'])) $lead_status = $_REQUEST['lead_status'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	
	$where_clauses = Array();

	if(isset($last_name) && $last_name != "") array_push($where_clauses, "last_name like '$last_name%'");
	if(isset($first_name) && $first_name != "")	array_push($where_clauses, "first_name like '$first_name%'");
	if(isset($company) && $company != "")	array_push($where_clauses, "company like '$company%'");
	if(isset($lead_source) && $lead_source != "") array_push($where_clauses, "lead_source = '$lead_source'");
	if(isset($industry) && $industry != "") array_push($where_clauses, "industry = '$industry'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "phone like '%$phone%'");
	if(isset($email) && $email != "") array_push($where_clauses, "email like '$email%'");
	if(isset($mobile) && $mobile != "") array_push($where_clauses, "mobile like '%$mobile%'");
	if(isset($lead_status) && $lead_status != "") array_push($where_clauses, "lead_status =  '$lead_status'");
	if(isset($rating) && $rating != "") array_push($where_clauses, "rating = '$rating'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "address_street like '$address_street%'");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "address_city like '$address_city%'");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "address_state like '$address_state%'");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "address_postalcode like '$address_postalcode%'");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "address_country like '$address_country%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "leads.assigned_user_id='$current_user->id'");
	
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
	
	if (isset($first_name)) $search_form->assign("FIRST_NAME", $_REQUEST['first_name']);
	if (isset($last_name)) $search_form->assign("LAST_NAME", $_REQUEST['last_name']);
	if (isset($company)) $search_form->assign("COMPANY", $_REQUEST['company']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
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

		if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], $lead_source));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], ''));

		if (isset($lead_status)) $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($app_list_strings['lead_status_dom'], $lead_status));
		else $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($app_list_strings['lead_status_dom'], ''));

		if (isset($rating)) $search_form->assign("RATING_OPTIONS", get_select_options($app_list_strings['rating_dom'], $rating));
		else $search_form->assign("RATING_OPTIONS", get_select_options($app_list_strings['rating_dom'], ''));

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options($app_list_strings['industry_dom'], $industry));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options($app_list_strings['industry_dom'], ''));	

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

listView($current_module_strings['LBL_LIST_FORM_TITLE'] , "LEAD", 'modules/Leads/ListView.html', $seedLead, "first_name, last_name");
?>
