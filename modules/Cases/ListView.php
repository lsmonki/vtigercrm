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
 * $Header:  vtiger_crm/sugarcrm/modules/Cases/ListView.php,v 1.3 2004/10/29 09:55:09 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Cases/Case.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Cases');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('case_list');

global $currentModule;

global $theme;

if (!isset($where)) $where = "";

$seedCase = new aCase();
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['account_name'])) $account_name = $_REQUEST['account_name'];
	if (isset($_REQUEST['status'])) $status = $_REQUEST['status'];
	if (isset($_REQUEST['number'])) $number = $_REQUEST['number'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "cases.name like '".PearDatabase::quote($name)."%'");
	if(isset($account_name) && $account_name != "") array_push($where_clauses, "accounts.name like '".PearDatabase::quote($account_name)."%'");
	if(isset($status) && $status != "") array_push($where_clauses, "cases.status = '".PearDatabase::quote($status)."'");
	if(isset($number) && $number != "") array_push($where_clauses, "cases.number like '".PearDatabase::quote($number)."%'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "cases.assigned_user_id='$current_user->id'");
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	if (!empty($assigned_user_id)) {
		if (!empty($where)) {
			$where .= " AND ";
		}
		$where .= "cases.assigned_user_id IN(";
		foreach ($assigned_user_id as $key => $val) {
			$where .= "'".PearDatabase::quote($val)."'";
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Cases/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if(isset($name)) $search_form->assign("NAME", $name);
	if(isset($account_name)) $search_form->assign("ACCOUNT_NAME", $account_name);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($number)) $search_form->assign("NUMBER", $number);

		$case_status_dom = & $app_list_strings['case_status_dom'];
		array_unshift($case_status_dom, '');
		if (isset($status)) $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($case_status_dom, $status));
		else $search_form->assign("STATUS_OPTIONS", get_select_options_with_id($case_status_dom, ''));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

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




$newForm = null;


$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Cases/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
$ListView->setQuery($where, "", "name", "CASE");
$ListView->processListView($seedCase, "main", "CASE");

?>
