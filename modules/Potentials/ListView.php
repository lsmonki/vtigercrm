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

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Opportunities/Opportunity.php');
require_once('include/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/listview.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Opportunities');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('opportunity_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedOpportunity = new Opportunity();
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['account_name'])) $account_name = $_REQUEST['account_name'];
	if (isset($_REQUEST['date_closed'])) $date_closed = $_REQUEST['date_closed'];

	if (isset($_REQUEST['amount'])) $amount = $_REQUEST['amount'];
	if (isset($_REQUEST['next_step'])) $next_step = $_REQUEST['next_step'];
	if (isset($_REQUEST['probability'])) $probability = $_REQUEST['probability'];

	if (isset($_REQUEST['lead_source'])) $lead_source = $_REQUEST['lead_source']; 
	if (isset($_REQUEST['opportunity_type'])) $opportunity_type = $_REQUEST['opportunity_type']; 
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage']; 
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "opportunities.name like '$name%'");
	if(isset($account_name) && $account_name != "") array_push($where_clauses, "accounts.name like '$account_name%'");
	if(isset($lead_source) && $lead_source != "") array_push($where_clauses, "opportunities.lead_source = '$lead_source'");
	if(isset($opportunity_type) && $opportunity_type != "") array_push($where_clauses, "opportunities.opportunity_type = '$opportunity_type'");
	if(isset($amount) && $amount != "") array_push($where_clauses, "opportunities.amount like '$amount%%'");
	if(isset($next_step) && $next_step != "") array_push($where_clauses, "opportunities.next_step like '$next_step%'");
	if(isset($sales_stage) && $sales_stage != "") array_push($where_clauses, "opportunities.sales_stage = '$sales_stage'");
	if(isset($probability) && $probability != "") array_push($where_clauses, "opportunities.probability like '$probability%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "opportunities.assigned_user_id='$current_user->id'");

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
	$search_form=new XTemplate ('modules/Opportunities/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($account_name)) $search_form->assign("ACCOUNT_NAME", $account_name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
		if (isset($amount)) $search_form->assign("AMOUNT", $amount);
		if (isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		if (isset($date_closed)) $search_form->assign("DATE_CLOSED", $date_closed);
		if (isset($next_step)) $search_form->assign("NEXT_STEP", $next_step);
		if (isset($probability)) $search_form->assign("PROBABILITY", $probability);
		if (isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		if (isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);

		if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], $lead_source));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], ''));
		if (isset($opportunity_type)) $search_form->assign("TYPE_OPTIONS", get_select_options($app_list_strings['opportunity_type_dom'], $opportunity_type));
		else $search_form->assign("TYPE_OPTIONS", get_select_options($app_list_strings['opportunity_type_dom'], ''));
		$sales_stage_dom = & $app_list_strings['sales_stage_dom'];
		array_unshift($sales_stage_dom, '');
		if (isset($sales_stage)) $search_form->assign("SALES_STAGE_OPTIONS", get_select_options($app_list_strings['sales_stage_dom'], $sales_stage));
		else $search_form->assign("SALES_STAGE_OPTIONS", get_select_options($app_list_strings['sales_stage_dom'], ''));

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

listView($current_module_strings['LBL_LIST_FORM_TITLE'] , "OPPORTUNITY", 'modules/Opportunities/ListView.html', $seedOpportunity, "name");

?>
