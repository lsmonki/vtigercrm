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
 * $Header:  vtiger_crm/sugarcrm/modules/Home/UnifiedSearch.php,v 1.1 2004/08/17 15:05:06 gjayakrishnan Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('include/logging.php');

global $mod_strings;

function build_account_where_clause ($the_query_string) {
	$where_clauses = Array();

	array_push($where_clauses, "name like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "phone_alternate like '%$the_query_string%'");
		array_push($where_clauses, "phone_fax like '%$the_query_string%'");
		array_push($where_clauses, "phone_office like '%$the_query_string%'");
	}
	
	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}
	$log = LoggerManager::getLogger('account_unified_search');
	$log->info("Here is the where clause for the Accounts list view: $the_where");
	
	return $the_where;
}

function build_contact_where_clause ($the_query_string) {
	$where_clauses = Array();

	array_push($where_clauses, "last_name like '$the_query_string%'");
	array_push($where_clauses, "first_name like '$the_query_string%'");
	array_push($where_clauses, "assistant like '$the_query_string%'");
	if (is_numeric($the_query_string)) {
		array_push($where_clauses, "phone_home like '%$the_query_string%'");
		array_push($where_clauses, "phone_mobile like '%$the_query_string%'");
		array_push($where_clauses, "phone_work like '%$the_query_string%'");
		array_push($where_clauses, "phone_other like '%$the_query_string%'");
		array_push($where_clauses, "phone_fax like '%$the_query_string%'");
		array_push($where_clauses, "assistant_phone like '%$the_query_string%'");
	}
	
	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}
	$log = LoggerManager::getLogger('contact_unified_search');
	$log->info("Here is the where clause for the Contacts list view: $the_where");
	
	return $the_where;
}

function build_opportunity_where_clause ($the_query_string) {
	$where_clauses = Array();

	array_push($where_clauses, "name like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}
	$log = LoggerManager::getLogger('opportunity_unified_search');
	$log->info("Here is the where clause for the Opportunities list view: $the_where");
	
	return $the_where;
}

function build_case_where_clause ($the_query_string) {
	$where_clauses = Array();

	array_push($where_clauses, "cases.name like '$the_query_string%'");
	if (is_numeric($the_query_string)) array_push($where_clauses, "cases.number like '$the_query_string%'");

	$the_where = "";
	foreach($where_clauses as $clause)
	{
		if($the_where != "") $the_where .= " or ";
		$the_where .= $clause;
	}
	$log = LoggerManager::getLogger('case_unified_search');
	$log->info("Here is the where clause for the Cases list view: $the_where");
	
	return $the_where;
}

//main
echo get_module_title("", "Search Results", true); 
echo "\n<BR>\n";
if(isset($_REQUEST['query_string']) && preg_match("/[\w]/", $_REQUEST['query_string'])) {
	//get accounts
	$where = build_account_where_clause($_REQUEST['query_string']);
	echo "<table><td><tr>\n";
	include ("modules/Accounts/ListView.php");

	//get contacts
	$where = build_contact_where_clause($_REQUEST['query_string']);
	echo "<table><td><tr>\n";
	include ("modules/Contacts/ListView.php");

	//get opportunities
	$where = build_opportunity_where_clause($_REQUEST['query_string']);
	echo "<table><td><tr>\n";
	include ("modules/Opportunities/ListView.php");

	//get cases
	$where = build_case_where_clause($_REQUEST['query_string']);
	echo "<table><td><tr>\n";
	include ("modules/Cases/ListView.php");
}
else {
	echo "<br><br><em>".$mod_strings['ERR_ONE_CHAR']."</em>";
	//echo "</td></tr></table>\n";
}


?>
