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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/Popup.php,v 1.15 2005/04/19 15:33:44 rank Exp $
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
require_once('include/uifromdbutil.php');

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

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$url_string = '';
$sorder = 'ASC';
if(isset($_REQUEST['sorder']) && $_REQUEST['sorder'] != '')
$sorder = $_REQUEST['sorder'];
if($popuptype!='') $url_string .= "&popuptype=".$popuptype;
$smodule ="";
// Added for  Vendor popup
if(isset($_REQUEST['smodule']) && $_REQUEST['smodule'] !='')
{
        $smodule = $_REQUEST['smodule'];
	$url_string .= '&smodule='.$_REQUEST['smodule'];
	require_once("modules/Products/Vendor.php");
	$vendor_focus = new Vendor();
	$vendor_cnt = $vendor_focus->get_related_contacts($_REQUEST['recordid']);
	if($vendor_cnt !='')
	{
		$search_query .= " and contactdetails.contactid NOT IN (".$vendor_cnt.")";
	}
}

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['title'])) $title = $_REQUEST['title'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

	if(isset($lastname) && $lastname != "") {
			array_push($where_clauses, "contactdetails.lastname like ".PearDatabase::quote($lastname.'%')."");
			$url_string .= "&lastname=".$lastname;
	}
	if(isset($firstname) && $firstname != "") {
			array_push($where_clauses, "contactdetails.firstname like ".PearDatabase::quote($firstname.'%')."");
			$url_string .= "&firstname=".$firstname;
	}
	if(isset($title) && $title != "")	{
			array_push($where_clauses, "contactdetails.title like ".PearDatabase::quote("%".$title.'%')."");
			$url_string .= "&title=".$title;
	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=on";
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
	$search_form->assign("SMODULE",$smodule);
        $search_form->assign("RETURNID",$_REQUEST['recordid']);
        $search_form->assign("RETURN_MODULE",$_REQUEST['return_module']);

	if ($order_by !='') $search_form->assign("ORDER_BY", $order_by);
	if ($sorder !='') $search_form->assign("SORDER", $sorder);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($title)) $search_form->assign("TITLE", $_REQUEST['title']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	$search_form->assign("ALPHABETICAL",AlphabeticalSearch('Contacts','Popup&smodule='.$smodule,'lastname','true','basic',$popuptype,$_REQUEST['recordid'],$_REQUEST['return_module']));
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

$xtpl->assign("SMODULE",$smodule);
//Retreive the list from Database
$list_query = getListQuery("Contacts");
$list_query .= $search_query; 

if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}
if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by.' '.$sorder;
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

// Setting the record count string
if ($navigation_array['start'] == 1)
{
	if($noofrows != 0)
	$start_rec = $navigation_array['start'];
	else
	$start_rec = 0;
	if($noofrows > $list_max_entries_per_page)
	{
		$end_rec = $navigation_array['start'] + $list_max_entries_per_page - 1;
	}
	else
	{
		$end_rec = $noofrows;
	}
	
}
else
{
	if($navigation_array['next'] > $list_max_entries_per_page)
	{
		$start_rec = $navigation_array['next'] - $list_max_entries_per_page;
		$end_rec = $navigation_array['next'] - 1;
	}
	else
	{
		$start_rec = $navigation_array['prev'] + $list_max_entries_per_page;
		$end_rec = $noofrows;
	}
}
$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
$focus->list_mode="search";
$focus->popup_type=$popuptype;

$listview_header = getSearchListViewHeader($focus,"Contacts",$url_string,$sorder,$order_by);
$xtpl->assign("LISTHEADER", $listview_header);

$listview_entries = getSearchListViewEntries($focus,"Contacts",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if($order_by !='')
$url_string .="&order_by=".$order_by;
if($sorder !='')
$url_string .="&sorder=".$sorder;

$url_string .="&recordid=".$_REQUEST['recordid'].'&return_module='.$_REQUEST['return_module'];

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Contacts","Popup");
$xtpl->assign("NAVIGATION", $navigationOutput);
$xtpl->assign("RECORD_COUNTS", $record_string);

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
