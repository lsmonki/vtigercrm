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
require_once('modules/Accounts/Account.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/database/PearDatabase.php');
require_once('include/ComboUtil.php');
require_once('include/utils.php');

global $app_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('account_list');

global $currentModule;
global $theme;

$popuptype = '';
$popuptype = $_REQUEST["popuptype"];
// Get _dom arrays from Database
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

$seedAccount = new Account();
if(isset($_REQUEST['query']))
{
	// we have a query
	if (isset($_REQUEST['name'])) $name = $_REQUEST['name'];
	if (isset($_REQUEST['website'])) $website = $_REQUEST['website'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();
/*
//Added for Custom Field Search
$sql="select * from customfields inner join customfieldtypemapping on customfields.uitype=customfieldtypemapping.uitype where module='Accounts' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'column_name');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
                $str=" accountcf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
        }
}
//upto this added for Custom Field
*/	
	if(isset($name) && $name != "") array_push($where_clauses, "account.accountname like ".PearDatabase::quote($name."%"));
	if(isset($website) && $website != "") array_push($where_clauses, "account.website like ".PearDatabase::quote("%".$website."%"));
	if(isset($phone) && $phone != "") array_push($where_clauses, "(account.phone like ".PearDatabase::quote("%".$phone."%")." OR account.otherphone like ".PearDatabase::quote("%".$phone."%")." OR account.fax like ".PearDatabase::quote("%".$phone."%").")");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "(accountbillads.city like ".PearDatabase::quote($address_city."%")." OR accountshipads.city like ".PearDatabase::quote($address_city."%").")");
	if(isset($ownership) && $ownership != "") array_push($where_clauses, "account.ownership like ".PearDatabase::quote($ownership."%"));
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "crmentity.smownerid='$current_user->id'");

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
			$where .= PearDatabase::quote($val);
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
	// Stick the form header out there.
	$search_form=new XTemplate ('modules/Accounts/PopupSearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);
	
	$search_form->assign("POPUPTYPE",$popuptype);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($website)) $search_form->assign("WEBSITE", $website);
	if (isset($phone)) $search_form->assign("PHONE", $phone);
	if (isset($phone)) $search_form->assign("ADDRESS_CITY", $address_city);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);

	$search_form->parse("main");
	$search_form->out("main");

	echo get_form_footer();
	echo "\n<BR>\n";
}

/*
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Accounts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);

$ListView->setQuery($where, "", "accountname", "ACCOUNT");
$ListView->processListView($seedAccount, "main", "ACCOUNT");
*/

$focus = new Account();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Accounts/Popup.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);
$xtpl->assign("THEME_PATH",$theme_path);



//Retreive the list from Database
$query = getListQuery("Accounts");
if(isset($where) && $where != '')
{
        $query .= ' and '.$where;
}
$list_result = $adb->query($query);

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

$listview_header = getSearchListViewHeader($focus,"Accounts");
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getSearchListViewEntries($focus,"Accounts",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);
$query_val = 'false';

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=Popup&module=Accounts&start='.$navigation_array['start'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=Popup&module=Accounts&start='.$navigation_array['end'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=Popup&module=Accounts&start='.$navigation_array['next'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=Popup&module=Accounts&start='.$navigation_array['prev'].'&query='.$query_val.'&popuptype='.$popuptype.'"><b>Prev</b></a>';
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
