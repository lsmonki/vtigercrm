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
 * $Header:  vtiger_crm/sugarcrm/modules/Accounts/EditView.php,v 1.7 2005/01/13 09:04:59 jack Exp $
 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Accounts/Account.php');
require_once('modules/Accounts/Forms.php');
require_once('include/CustomFieldUtil.php');
require_once('include/ComboUtil.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = new Account();

if(isset($_REQUEST['record'])) 
{
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
//retreiving the combo values array
$comboFieldNames = Array('account_type'=>'account_type_dom'
                      ,'industry'=>'industry_dom');
$comboFieldArray = getComboArray($comboFieldNames);


require_once($theme_path.'layout_utils.php');

$log->info("Account detail view");

$xtpl=new XTemplate ('modules/Accounts/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ANNUAL_REVENUE", $focus->annual_revenue);
$xtpl->assign("BILLING_ADDRESS_STREET", $focus->billing_address_street);
$xtpl->assign("BILLING_ADDRESS_CITY", $focus->billing_address_city);
$xtpl->assign("BILLING_ADDRESS_STATE", $focus->billing_address_state);
$xtpl->assign("BILLING_ADDRESS_POSTALCODE", $focus->billing_address_postalcode);
$xtpl->assign("BILLING_ADDRESS_COUNTRY", $focus->billing_address_country);
$xtpl->assign("DATE_ENTERED", $focus->date_entered);
$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("EMPLOYEES", $focus->employees);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);
$xtpl->assign("INDUSTRY", $focus->industry);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");
$xtpl->assign("OWNERSHIP", $focus->ownership);
$xtpl->assign("PARENT_ID", $focus->parent_id);
$xtpl->assign("PHONE_ALTERNATE", $focus->phone_alternate);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("PHONE_OFFICE", $focus->phone_office);
$xtpl->assign("RATING", $focus->rating);
$xtpl->assign("SHIPPING_ADDRESS_STREET", $focus->shipping_address_street);
$xtpl->assign("SHIPPING_ADDRESS_CITY", $focus->shipping_address_city);
$xtpl->assign("SHIPPING_ADDRESS_STATE", $focus->shipping_address_state);
$xtpl->assign("SHIPPING_ADDRESS_COUNTRY", $focus->shipping_address_country);
$xtpl->assign("SHIPPING_ADDRESS_POSTALCODE", $focus->shipping_address_postalcode);
$xtpl->assign("SIC_CODE", $focus->sic_code);
$xtpl->assign("TICKER_SYMBOL", $focus->ticker_symbol);
$xtpl->assign("ACCOUNT_TYPE", $focus->account_type);
$xtpl->assign("WEBSITE", $focus->website);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($comboFieldArray['account_type_dom'], $focus->account_type));
$xtpl->assign("INDUSTRY_OPTIONS", get_select_options_with_id($comboFieldArray['industry_dom'], $focus->industry));

if(isset($_REQUEST['parent_id'])) $xtpl->assign("PARENT_ID", $_REQUEST['parent_id']);
if(isset($_REQUEST['parent_name'])) $xtpl->assign("PARENT_NAME", urldecode($_REQUEST['parent_name']));
if(isset($_REQUEST['billing_address_street'])) $xtpl->assign("BILLING_ADDRESS_STREET", urldecode($_REQUEST['billing_address_street']));
if(isset($_REQUEST['billing_address_city'])) $xtpl->assign("BILLING_ADDRESS_CITY", urldecode($_REQUEST['billing_address_city']));
if(isset($_REQUEST['billing_address_state'])) $xtpl->assign("BILLING_ADDRESS_STATE", urldecode($_REQUEST['billing_address_state']));
if(isset($_REQUEST['billing_address_postalcode'])) $xtpl->assign("BILLING_ADDRESS_POSTALCODE", urldecode($_REQUEST['billing_address_postalcode']));
if(isset($_REQUEST['billing_address_country'])) $xtpl->assign("BILLING_ADDRESS_COUNTRY", urldecode($_REQUEST['billing_address_country']));
if(isset($_REQUEST['ownership'])) $xtpl->assign("OWNERSHIP", urldecode($_REQUEST['ownership']));
if(isset($_REQUEST['website'])) $xtpl->assign("WEBSITE", urldecode($_REQUEST['website']));
if(isset($_REQUEST['industry'])) $xtpl->assign("INDUSTRY_OPTIONS", get_select_options_with_id($app_list_strings['industry_dom'], urldecode($_REQUEST['industry'])));
if(isset($_REQUEST['account_type'])) $xtpl->assign("ACCOUNT_TYPE_OPTIONS", get_select_options_with_id($app_list_strings['account_type_dom'], urldecode($_REQUEST['account_type'])));
 $xtpl->assign("CALENDAR_LANG", "en");$xtpl->assign("CALENDAR_DATEFORMAT", parse_calendardate($app_strings['NTC_DATE_FORMAT']));

//CustomField
//$date_format = parse_calendardate($app_strings['NTC_DATE_FORMAT']);
$custfld = CustomFieldEditView($focus->id, "Accounts", "accountcf", "accountid", $app_strings, $theme);
$xtpl->assign("CUSTOMFIELD", $custfld);

$xtpl->parse("main");

$xtpl->out("main");

?>
