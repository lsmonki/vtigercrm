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

define('IN_PHPBB', true);

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/MessageBoard/MessageBoard.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/listview.php');

global $app_strings;
global $app_list_strings;
global $current_language;

$current_module_strings = return_module_language($current_language, 'MessageBoard');

global $list_max_entries_per_page;
global $urlPrefix;

global $currentModule;

global $theme;

if (!isset($where)) $where = "";
$seedMessageBoard = new MessageBoard();

if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['topic'])) $topic = $_REQUEST['topic'];
	if (isset($_REQUEST['author'])) $author = $_REQUEST['author'];
	$where_clauses = Array();

	if(isset($topic) && $topic != "") array_push($where_clauses, "t.topic_title like '$topic%'");
	if(isset($author) && $author != "") array_push($where_clauses, "first.username like '$author%'");
	$where = "";
	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false')
 {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/MessageBoard/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	if (isset($topic)) $search_form->assign("TOPIC", $_REQUEST['topic']);
	if (isset($author)) $search_form->assign("AUTHOR", $_REQUEST['author']);
	//if (isset($age)) $search_form->assign("AGE", $_REQUEST['age']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	
	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");
	
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') { 
		//if(isset($date_entered)) $search_form->assign("DATE_ENTERED", $date_entered);
		//if(isset($date_modified)) $search_form->assign("DATE_MODIFIED", $date_modified);
		//if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		//if(isset($do_not_call)) $search_form->assign("DO_NOT_CALL", $do_not_call);
		//if(isset($phone)) $search_form->assign("PHONE", $phone);
		//if(isset($email)) $search_form->assign("EMAIL", $email);
		//if(isset($mobile)) $search_form->assign("MOBILE", $mobile);
		//if(isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		//if(isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		//if(isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		//if(isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		//if(isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);

		//if (isset($lead_source)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], $lead_source));
		//else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($app_list_strings['lead_source_dom'], ''));

		//if (isset($lead_status)) $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($app_list_strings['lead_status_dom'], $lead_status));
		//else $search_form->assign("LEAD_STATUS_OPTIONS", get_select_options($app_list_strings['lead_status_dom'], ''));

		//if (isset($rating)) $search_form->assign("RATING_OPTIONS", get_select_options($app_list_strings['rating_dom'], $rating));
		//else $search_form->assign("RATING_OPTIONS", get_select_options($app_list_strings['rating_dom'], ''));

		//if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options($app_list_strings['industry_dom'], $industry));
		//else $search_form->assign("INDUSTRY_OPTIONS", get_select_options($app_list_strings['industry_dom'], ''));	

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
listView($current_module_strings['LBL_LIST_FORM_TITLE'] , "MESSAGEBOARD", 'modules/MessageBoard/ListView.html', $seedMessageBoard, "last_post");
?>
