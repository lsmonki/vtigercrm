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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Contacts/ListView.php,v 1.14 2005/02/24 19:58:11 jack Exp $
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

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom');
$comboFieldArray = getComboArray($comboFieldNames);

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

$focus = new Contact();
if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['firstname'])) $firstname = $_REQUEST['firstname'];
	if (isset($_REQUEST['lastname'])) $lastname = $_REQUEST['lastname'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
	if (isset($_REQUEST['leadsource'])) $leadsource = $_REQUEST['leadsource'];
	if (isset($_REQUEST['donotcall'])) $donotcall = $_REQUEST['donotcall'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['yahooid'])) $yahooid = $_REQUEST['yahooid'];
	if (isset($_REQUEST['assistant'])) $assistant = $_REQUEST['assistant'];
	if (isset($_REQUEST['emailoptout'])) $emailoptout = $_REQUEST['emailoptout'];
	if (isset($_REQUEST['mailingstreet'])) $mailingstreet = $_REQUEST['mailingstreet'];
	if (isset($_REQUEST['mailingcity'])) $mailingcity = $_REQUEST['mailingcity'];
	if (isset($_REQUEST['mailingstreet'])) $mailingstreet = $_REQUEST['mailingstreet'];
	if (isset($_REQUEST['mailingzip'])) $mailingzip = $_REQUEST['mailingzip'];
	if (isset($_REQUEST['mailingcountry'])) $mailingcountry = $_REQUEST['mailingcountry'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];


	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field where tablename='contactscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
                $str=" contactscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
        }
}
//upto this added for Custom Field


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
	if(isset($leadsource) && $leadsource != "") {
			array_push($where_clauses, "contactsubdetails.leadsource = ".PearDatabase::quote($leadsource)."");
			$query_val .= "&leadsource=".$leadsource;
	}
	if(isset($donotcall) && $donotcall != "") {
			array_push($where_clauses, "contactdetails.donotcall = ".PearDatabase::quote($donotcall)."");
			$query_val .= "&donotcall=".$donotcall;
	}
	if(isset($phone) && $phone != "") {
			array_push($where_clauses, "(contactdetails.phone like ".PearDatabase::quote('%'.$phone.'%')." OR contactdetails.mobile like ".PearDatabase::quote('%'.$phone.'%')." OR contactdetails.fax like ".PearDatabase::quote('%'.$phone.'%').")");
			$query_val .= "&phone=".$phone;
	}
	if(isset($email) && $email != "") {
			array_push($where_clauses, "(contactdetails.email like ".PearDatabase::quote($email.'%').")");
			$query_val .= "&email=".$email;
	}
	if(isset($yahooid) && $yahooid != "") {
			array_push($where_clauses, "contactdetails.yahooid like ".PearDatabase::quote($yahooid.'%')."");
			$query_val .= "&yahooid=".$yahooid;
	}
	if(isset($assistant) && $assistant != "") {
			array_push($where_clauses, "contactsubdetails.assistant like ".PearDatabase::quote($assistant.'%')."");
			$query_val .= "&yahooid=".$yahooid;
	}
	if(isset($emailoptout) && $emailoptout != "") {
			array_push($where_clauses, "contactdetails.emailoptout = ".PearDatabase::quote($emailoptout)."");
			$query_val .= "&emailoptout=".$emailoptout;
	}
	if(isset($mailingstreet) && $mailingstreet != "") {
			array_push($where_clauses, "(contactaddress.mailingstreet like ".PearDatabase::quote($mailingstreet.'%')." OR contactaddress.otherstreet like ".PearDatabase::quote($mailingstreet.'%').")");
			$query_val .= "&mailingstreet=".$mailingstreet;
	}
	if(isset($mailingcity) && $mailingcity != "") {
			array_push($where_clauses, "(contactaddress.mailingcity like ".PearDatabase::quote($mailingcity.'%')." OR contactaddress.othercity like ".PearDatabase::quote($mailingcity.'%').")");
			$query_val .= "&mailingcity=".$mailingcity;
	}
	if(isset($mailingstate) && $mailingstate != "") {
			array_push($where_clauses, "(contactaddress.mailingstate like ".PearDatabase::quote($mailingstate.'%')." OR contactaddress.otherstate like ".PearDatabase::quote($mailingstate.'%').")");
			$query_val .= "&mailingstate=".$mailingstate;
	}
	if(isset($mailingzip) && $mailingzip != "") {
			array_push($where_clauses, "(contactaddress.mailingzip like ".PearDatabase::quote($mailingzip.'%')." OR contactaddress.otherzip like ".PearDatabase::quote($mailingzip.'%').")");
			$query_val .= "&mailingzip=".$mailingzip;
	}
	if(isset($mailingcountry) && $mailingcountry != "") {
			array_push($where_clauses, "(contactaddress.mailingcountry like ".PearDatabase::quote($mailingcountry.'%')." OR contactaddress.othercountry like ".PearDatabase::quote($mailingcountry.'%').")");
			$query_val .= "&mailingcountry=".$mailingcountry;
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
	$search_form=new XTemplate ('modules/Contacts/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	if (isset($firstname)) $search_form->assign("FIRST_NAME", $_REQUEST['firstname']);
	if (isset($lastname)) $search_form->assign("LAST_NAME", $_REQUEST['lastname']);
	if (isset($accountname)) $search_form->assign("ACCOUNT_NAME", $_REQUEST['accountname']);
	$search_form->assign("JAVASCRIPT", get_clear_form_js());

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if(isset($accountname)) $search_form->assign("ACCOUNT_NAME", $accountname);
		if(isset($createdtime)) $search_form->assign("DATE_ENTERED", $createdtime);
		if(isset($modifiedtime)) $search_form->assign("DATE_MODIFIED", $modifiedtime);
		if(isset($modified_user_id)) $search_form->assign("MODIFIED_USER_ID", $modified_user_id);
		if(isset($donotcall)) $search_form->assign("DO_NOT_CALL", $donotcall);
		if(isset($phone)) $search_form->assign("PHONE", $phone);
		if(isset($email)) $search_form->assign("EMAIL", $email);
		if(isset($yahooid)) $search_form->assign("YAHOO_ID", $yahooid);
		if(isset($assistant)) $search_form->assign("ASSISTANT", $assistant);
		if(isset($emailoptout)) $search_form->assign("EMAIL_OPT_OUT", $emailoptout);
		if(isset($mailingstreet)) $search_form->assign("ADDRESS_STREET", $mailingstreet);
		if(isset($mailingcity)) $search_form->assign("ADDRESS_CITY", $mailingcity);
		if(isset($mailingstate)) $search_form->assign("ADDRESS_STATE", $mailingstate);
		if(isset($mailingzip)) $search_form->assign("ADDRESS_POSTALCODE", $mailingzip);
		if(isset($mailingcountry)) $search_form->assign("ADDRESS_COUNTRY", $mailingcountry);

		if (isset($leadsource)) $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], $lead_source, $_REQUEST['advanced']));
		else $search_form->assign("LEAD_SOURCE_OPTIONS", get_select_options($comboFieldArray['leadsource_dom'], '', $_REQUEST['advanced']));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

//Added for Custom Field Search
$sql="select * from field where tablename='contactscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "contactscf","contactscf", "contactid", $app_strings,$theme,$column,$fieldlabel);
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

//Retreive the list from Database
$list_query = getListQuery("Contacts");
if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}

$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
        $list_query .= ' ORDER BY '.$order_by;
        $url_qry .="&order_by=".$order_by;
}

$list_result = $adb->query($list_query);

//Constructing the list view 

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Contacts/ListView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

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

$listview_header = getListViewHeader($focus,"Contacts");
$xtpl->assign("LISTHEADER", $listview_header);



$listview_entries = getListViewEntries($focus,"Contacts",$list_result,$navigation_array);
$xtpl->assign("LISTHEADER", $listview_header);
$xtpl->assign("LISTENTITY", $listview_entries);


if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Contacts'.$url_qry.'&start=1"><b>Start</b></a>';
}
else
{
	$startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
	$endoutput = '<a href="index.php?action=index&module=Contacts'.$url_qry.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
	$endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
	$nextoutput = '<a href="index.php?action=index&module=Contacts'.$url_qry.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
	$nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
	$prevoutput = '<a href="index.php?action=index&module=Contacts'.$url_qry.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
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
