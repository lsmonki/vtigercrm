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
global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Accounts');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('account_list');

global $currentModule;

global $theme;

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
	if (isset($_REQUEST['annual_revenue'])) $annual_revenue = $_REQUEST['annual_revenue'];
	if (isset($_REQUEST['email'])) $email = $_REQUEST['email'];
	if (isset($_REQUEST['employees'])) $employees = $_REQUEST['employees'];
	if (isset($_REQUEST['industry'])) $industry = $_REQUEST['industry'];
	if (isset($_REQUEST['ownership'])) $ownership = $_REQUEST['ownership'];
	if (isset($_REQUEST['rating'])) $rating = $_REQUEST['rating'];
	if (isset($_REQUEST['sic_code'])) $sic_code = $_REQUEST['sic_code'];
	if (isset($_REQUEST['ticker_symbol'])) $ticker_symbol = $_REQUEST['ticker_symbol'];
	if (isset($_REQUEST['account_type'])) $account_type = $_REQUEST['account_type'];
	if (isset($_REQUEST['address_street'])) $address_street = $_REQUEST['address_street'];
	if (isset($_REQUEST['address_city'])) $address_city = $_REQUEST['address_city'];
	if (isset($_REQUEST['address_state'])) $address_state = $_REQUEST['address_state'];
	if (isset($_REQUEST['address_country'])) $address_country = $_REQUEST['address_country'];
	if (isset($_REQUEST['address_postalcode'])) $address_postalcode = $_REQUEST['address_postalcode'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];

	$where_clauses = Array();

	if(isset($name) && $name != "") array_push($where_clauses, "accounts.name like '$name%'");
	if(isset($website) && $website != "") array_push($where_clauses, "accounts.website like '$website%'");
	if(isset($phone) && $phone != "") array_push($where_clauses, "(accounts.phone_office like '%$phone%' OR accounts.phone_alternate like '%$phone%' OR accounts.phone_fax like '%$phone%')");
	if(isset($annual_revenue) && $annual_revenue != "") array_push($where_clauses, "accounts.annual_revenue like '$annual_revenue%'");
	if(isset($address_street) && $address_street != "") array_push($where_clauses, "(accounts.billing_address_street like '$address_street%' OR accounts.shipping_address_street like '$address_street%')");
	if(isset($address_city) && $address_city != "") array_push($where_clauses, "(accounts.billing_address_city like '$address_city%' OR accounts.shipping_address_city like '$address_city%')");
	if(isset($address_state) && $address_state != "") array_push($where_clauses, "(accounts.billing_address_state like '$address_state%' OR accounts.shipping_address_state like '$address_state%')");
	if(isset($address_postalcode) && $address_postalcode != "") array_push($where_clauses, "(accounts.billing_address_postalcode like '$address_postalcode%' OR accounts.shipping_address_postalcode like '$address_postalcode%')");
	if(isset($address_country) && $address_country != "") array_push($where_clauses, "(accounts.billing_address_country like '$address_country%' OR accounts.shipping_address_country like '$address_country%')");
	if(isset($email) && $email != "") array_push($where_clauses, "(contacts.email1 like '$email%' OR contacts.email2 like '$email%')");
	if(isset($industry) && $industry != "") array_push($where_clauses, "accounts.industry = '$industry'");
	if(isset($ownership) && $ownership != "") array_push($where_clauses, "accounts.ownership like '$ownership%'");
	if(isset($rating) && $rating != "") array_push($where_clauses, "accounts.rating like '$rating%'");
	if(isset($sic_code) && $sic_code != "") array_push($where_clauses, "accounts.sic_code like '$sic_code%'");
	if(isset($ticker_symbol) && $ticker_symbol != "") array_push($where_clauses, "accounts.ticker_symbol like '$ticker_symbol%'");
	if(isset($account_type) && $account_type != "") array_push($where_clauses, "accounts.account_type = '$account_type'");
	if(isset($current_user_only) && $current_user_only != "") array_push($where_clauses, "accounts.assigned_user_id='$current_user->id'");

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
		$where .= "accounts.assigned_user_id IN(";
		foreach ($assigned_user_id as $key => $val) {
			$where .= "'$val'";
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

	if(isset($current_user_only)) $search_form->assign("CURRENT_USER_ONLY", "checked");

	echo get_form_header($current_module_strings['LBL_SEARCH_FORM_TITLE'], "", false);
	if (isset($_REQUEST['advanced']) && $_REQUEST['advanced'] == 'true') {
		if (isset($annual_revenue)) $search_form->assign("ANNUAL_REVENUE", $annual_revenue);
		if (isset($address_street)) $search_form->assign("ADDRESS_STREET", $address_street);
		if (isset($address_city)) $search_form->assign("ADDRESS_CITY", $address_city);
		if (isset($address_state)) $search_form->assign("ADDRESS_STATE", $address_state);
		if (isset($address_country)) $search_form->assign("ADDRESS_COUNTRY", $address_country);
		if (isset($address_postalcode)) $search_form->assign("ADDRESS_POSTALCODE", $address_postalcode);
		if (isset($email)) $search_form->assign("EMAIL", $email);
		if (isset($ownership)) $search_form->assign("OWNERSHIP", $ownership);
		if (isset($rating)) $search_form->assign("RATING", $rating);
		if (isset($sic_code)) $search_form->assign("SIC_CODE", $sic_code);
		if (isset($ticker_symbol)) $search_form->assign("TICKER_SYMBOL", $ticker_symbol);

		if (isset($industry)) $search_form->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], $industry));
		else $search_form->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], ''));
		if (isset($account_type)) $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], $account_type));
		else $search_form->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], ''));

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



$ListView = new ListView();
$ListView->initNewXTemplate('modules/Accounts/ListView.html',$current_module_strings);
$ListView->setHeaderTitle($current_module_strings['LBL_LIST_FORM_TITLE']);

$ListView->setQuery($where, "", "name", "ACCOUNT");
$ListView->processListView($seedAccount, "main", "ACCOUNT");
?>
