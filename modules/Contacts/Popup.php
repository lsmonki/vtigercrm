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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Popup.php,v 1.10 2005/03/05 05:20:11 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once("data/Tracker.php");
require_once('modules/Contacts/Contact.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Contacts');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('contact_list');

global $currentModule;
global $theme;

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

$focus = new Contact();
$query_val = 'false';
if(isset($_REQUEST['query']))
{
	$query_val = 'true';
	// we have a query
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

	if(isset($lastname) && $lastname != "") {
			array_push($where_clauses, "contactdetails.lastname like ".PearDatabase::quote($lastname.'%')."");
			$query_val .= "&lastname=".$lastname;
	}
	if(isset($firstname) && $firstname != "") {
			array_push($where_clauses, "contactdetails.firstname like ".PearDatabase::quote($firstname.'%')."");
			$query_val .= "&firstname=".$firstname;
	}
	if(isset($accountname) && $accountname != "")	{
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%')."");
			$query_val .= "&accountname=".$accountname;
	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$query_val .= "&current_user_only=on";
	}

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
		$where .= "crmentity.smownerid IN(";
		foreach ($assigned_user_id as $key => $val) {
			$where .= "".PearDatabase::quote($val)."";
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Contacts/PopupSearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	$search_form->assign("POPUPTYPE",$popuptype);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($accountname)) $search_form->assign("COMPANY_NAME", $_REQUEST['accountname']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	$search_form->parse("main");
	$search_form->out("main");

	echo get_form_footer();
	echo "\n<BR>\n";
}

//Constructing the list view 
$focus = new Contact();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Contacts/Popup.html');
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] !='')
	$xtpl->assign("RETURN_MODULE",$_REQUEST['return_module']);
else 
	$xtpl->assign("RETURN_MODULE",'Emails');

//Retreive the list from Database
$list_query = getListQuery("Contacts");
if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}
$list_result = $adb->query($list_query);

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


$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"Contacts");
$xtpl->assign("LISTHEADER", $listview_header);



$listview_entries = getSearchListViewEntries($focus,"Contacts",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);


if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=Popup&module=Contacts&start='.$navigation_array['start'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=Popup&module=Contacts&start='.$navigation_array['end'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=Popup&module=Contacts&start='.$navigation_array['next'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=Popup&module=Contacts&start='.$navigation_array['prev'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Prev</b></a>';
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

/*
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/Contacts/ListView.html',$current_module_strings);
$ListView->setHeaderText("<table cellspacing='0' cellpadding='0'><tr><td><input type='button' class='button' onClick='document.location=\"index.php?module=Contacts&action=BusinessCard\"' name='addbusinesscard' value='{$current_module_strings['LBL_ADD_BUSINESSCARD']}'></td></tr></table>");
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE'] );
$ListView->setQuery($where, "", "firstname, lastname", "CONTACT");
$ListView->processListView($seedContact, "main", "CONTACT");
*/
?>
