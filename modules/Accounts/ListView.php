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

// Get _dom arrays from Database
$comboFieldNames = Array('accounttype'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);

// focus_list is the means of passing data to a ListView.
global $focus_list;

if (!isset($where)) $where = "";

if (isset($_REQUEST['order_by'])) $order_by = $_REQUEST['order_by'];

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	if (isset($_REQUEST['accountname'])) $name = $_REQUEST['accountname'];
	if (isset($_REQUEST['website'])) $website = $_REQUEST['website'];
	if (isset($_REQUEST['phone'])) $phone = $_REQUEST['phone'];
	if (isset($_REQUEST['annual_revenue'])) $annual_revenue = $_REQUEST['annual_revenue'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['employees'])) $employees = $_REQUEST['employees'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['ownership'])) $ownership = $_REQUEST['ownership'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['siccode'])) $sic_code = $_REQUEST['siccode'];
	if (isset($_REQUEST['tickersymbol'])) $ticker_symbol = $_REQUEST['tickersymbol'];
	if (isset($_REQUEST['accounttype'])) $account_type = $_REQUEST['accounttype'];
	if (isset($_REQUEST['bill_street'])) $address_street = $_REQUEST['bill_street'];
	if (isset($_REQUEST['bill_city'])) $address_city = $_REQUEST['bill_city'];
	if (isset($_REQUEST['bill_state'])) $address_state = $_REQUEST['bill_state'];
	if (isset($_REQUEST['bill_country'])) $address_country = $_REQUEST['bill_country'];
	if (isset($_REQUEST['bill_code'])) $address_postalcode = $_REQUEST['bill_code'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

//Added for Custom Field Search
$sql="select * from field where tablename='accountscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

        if(isset($customfield[$i]) && $customfield[$i] != '')
        {
                $str=" accountscf.".$column[$i]." like '$customfield[$i]%'";
                array_push($where_clauses, $str);
        }
}
//upto this added for Custom Field
	
	if(isset($name) && $name != "") array_push($where_clauses, "account.accountname like ".PearDatabase::quote($name."%"));
	if(isset($website) && $website != "") array_push($where_clauses, "account.website like ".PearDatabase::quote("%".$website."%"));
	if(isset($phone) && $phone != "") array_push($where_clauses, "(account.phone like ".PearDatabase::quote("%".$phone."%")." OR account.otherphone like ".PearDatabase::quote("%".$phone."%")." OR account.fax like ".PearDatabase::quote("%".$phone."%").")");
	if(isset($annual_revenue) && $annual_revenue != "") array_push($where_clauses, "account.annualrevenue like ".PearDatabase::quote($annual_revenue."%"));
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "(accountbillads.street like ".PearDatabase::quote($address_street."%")." OR accountshipads.street like ".PearDatabase::quote($address_street."%").")");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "(accountbillads.city like ".PearDatabase::quote($address_city."%")." OR accountshipads.city like ".PearDatabase::quote($address_city."%").")");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "(accountbillads.state like ".PearDatabase::quote($address_state."%")." OR accountshipads.state like ".PearDatabase::quote($address_state."%").")");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "(accountbillads.code like ".PearDatabase::quote($address_postalcode."%")." OR accountshipads.code like ".PearDatabase::quote($address_postalcode."%").")");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "(accountbillads.country like ".PearDatabase::quote($address_country."%")." OR accountshipads.country like ".PearDatabase::quote($address_country."%").")");
	if(isset($email) && $email != "") array_push($where_clauses, "(account.email1 like ".PearDatabase::quote($email."%")." OR account.email2 like ".PearDatabase::quote($email."%").")");
	if(isset($industry) && $industry != "") array_push($where_clauses, "account.industry = ".PearDatabase::quote($industry));
	if(isset($ownership) && $ownership != "") array_push($where_clauses, "account.ownership like ".PearDatabase::quote($ownership."%"));
	if(isset($rating) && $rating != "") array_push($where_clauses, "account.rating like ".PearDatabase::quote($rating."%"));
	if(isset($sic_code) && $sic_code != "") array_push($where_clauses, "account.siccode like ".PearDatabase::quote($sic_code."%"));
	if(isset($ticker_symbol) && $ticker_symbol != "") array_push($where_clauses, "account.tickersymbol like ".PearDatabase::quote($ticker_symbol."%"));
	if(isset($account_type) && $account_type != "") array_push($where_clauses, "account.account_type = ".PearDatabase::quote($account_type));
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
	$search_form=new XTemplate ('modules/Accounts/SearchForm.html');
	$search_form->assign("MOD", $current_module_strings);
	$search_form->assign("APP", $app_strings);

	$search_form->assign("JAVASCRIPT", get_clear_form_js());
	if (isset($name)) $search_form->assign("NAME", $name);
	if (isset($website)) $search_form->assign("WEBSITE", $website);
	if (isset($phone)) $search_form->assign("PHONE", $phone);
	if (isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($annual_revenue)) $search_form->assign("ANNUAL_REVENUE", $annual_revenue);
		if (isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if (isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if (isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);
		if (isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if (isset($email)) $search_form->assign("EMAIL", $email);
		if (isset($ownership)) $search_form->assign("OWNERSHIP", $ownership);
		if (isset($rating)) $search_form->assign("RATING", $rating);
		if (isset($sic_code)) $search_form->assign("SIC_CODE", $sic_code);
		if (isset($ticker_symbol)) $search_form->assign("TICKER_SYMBOL", $ticker_symbol);

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], $industry, $_REQUEST['advanced']));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options($comboFieldArray['industry_dom'], '', $_REQUEST['advanced']));
		if (isset($account_type)) $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options($comboFieldArray['account_type_dom'], $account_type, $_REQUEST['advanced']));
		else $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($comboFieldArray['account_type_dom'], '', $_REQUEST['advanced']));

		if (!empty($assigned_user_id)) $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), $assigned_user_id));
		else $search_form->assign("USER_FILTER", get_select_options_with_id(get_user_array(FALSE), ''));

//Added for Custom Field Search
$sql="select * from field where tablename='accountscf' order by fieldlabel";
$result=$adb->query($sql);
for($i=0;$i<$adb->num_rows($result);$i++)
{
        $column[$i]=$adb->query_result($result,$i,'columnname');
        $fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
        if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];
}
require_once('include/CustomFieldUtil.php');
$custfld = CustomFieldSearch($customfield, "accountscf", "accountcf", "accountid", $app_strings,$theme,$column,$fieldlabel);
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


/*
$ListView = new ListView();
$ListView->initNewXTemplate('modules/Accounts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);

$ListView->setQuery($where, "", "accountname", "ACCOUNT");
$ListView->processListView($seedAccount, "main", "ACCOUNT");
*/

$focus = new Account();

echo get_form_header($current_module_strings['LBL_LIST_FORM_TITLE'],'', false);
$xtpl=new XTemplate ('modules/Accounts/ListView.html');
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("IMAGE_PATH",$image_path);

//Retreive the list from Database
$query = getListQuery("Accounts");

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
          $query .= " and account_type like "."'%" .$defaultcv_criteria ."%'";          
   }


}

$url_qry = getURLstring($focus);

if(isset($order_by) && $order_by != '')
{
        $query .= ' ORDER BY '.$order_by;
        $url_qry .="&order_by=".$order_by;
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

$listview_header = getListViewHeader($focus,"Accounts");
$xtpl->assign("LISTHEADER", $listview_header);


$listview_entries = getListViewEntries($focus,"Accounts",$list_result,$navigation_array);
$xtpl->assign("LISTENTITY", $listview_entries);

if(isset($navigation_array['start']))
{
	$startoutput = '<a href="index.php?action=index&module=Accounts'.$url_qry.'&start=1"><b>Start</b></a>';
}
else
{
        $startoutput = '[ Start ]';
}
if(isset($navigation_array['end']))
{
        $endoutput = '<a href="index.php?action=index&module=Accounts'.$url_qry.'&start='.$navigation_array['end'].'"><b>End</b></a>';
}
else
{
        $endoutput = '[ End ]';
}
if(isset($navigation_array['next']))
{
        $nextoutput = '<a href="index.php?action=index&module=Accounts'.$url_qry.'&start='.$navigation_array['next'].'"><b>Next</b></a>';
}
else
{
        $nextoutput = '[ Next ]';
}
if(isset($navigation_array['prev']))
{
        $prevoutput = '<a href="index.php?action=index&module=Accounts'.$url_qry.'&start='.$navigation_array['prev'].'"><b>Prev</b></a>';
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
