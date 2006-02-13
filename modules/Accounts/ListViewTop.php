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
 * $Header: /cvsroot/vtigercrm/vtiger_crm/modules/Accounts/ListViewTop.php,v 1.2.2.2 2005/09/09 10:21:51 crouchingtiger Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
require_once("data/Tracker.php");
require_once('modules/Potentials/Opportunity.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');

global $app_strings;
global $current_user;
$current_module_strings = return_module_language($current_language, "Accounts");
$log = LoggerManager::getLogger('top accounts_list');

$list_query = 'select account.accountid, account.accountname, sum(potential.amount) as amount from potential inner join crmentity on (potential.potentialid=crmentity.crmid) inner join account on (potential.accountid=account.accountid) where crmentity.deleted=0 AND crmentity.smownerid="'.$current_user->id.'" and potential.sales_stage <> "'.$app_strings['LBL_CLOSE_WON'].'" and potential.sales_stage <> "'.$app_strings['LBL_CLOSE_LOST'].'" group by account.accountname order by 3 desc;';
$list_result=$adb->query($list_query);
$open_accounts_list = array();
$noofrows = min($adb->num_rows($list_result),7);
if (count($list_result)>0)
for($i=0;$i<$noofrows;$i++) 
{
  //$parent_name=getRelatedTo("Activities",$list_result,$i);
  $open_accounts_list[] = Array('accountid' => $adb->query_result($list_result,$i,'accountid'),
                                     'accountname' => $adb->query_result($list_result,$i,'accountname'),
                                     'amount' => $adb->query_result($list_result,$i,'amount'),
                                     );								 
}

$xtpl=new XTemplate ('modules/Accounts/ListViewTop.html');
$xtpl->assign("MOD", $current_module_strings);
$xtpl->assign("APP", $app_strings);
$xtpl->assign("CURRENCY_SYMBOL", getCurrencySymbol());

// Stick the form header out there.
//echo get_form_header($current_module_strings['LBL_TOP_ACCOUNTS'], '', false);

$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("RETURN_URL", "&return_module=$currentModule&return_action=DetailView&return_id=" . ((is_object($focus)) ? $focus->id : ""));

$oddRow = true;
foreach($open_accounts_list as $account)
{
	$account_fields = array(
		'ACCOUNT_ID' => $account['accountid'],
		'ACCOUNT_NAME' => $account['accountname'],
		'AMOUNT' => ($account['amount']),
	);

	$xtpl->assign("ACCOUNT", $account_fields);

	if($oddRow)
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'oddListRow');
    }
    else
    {
        //todo move to themes
		$xtpl->assign("ROW_COLOR", 'evenListRow');
    }
        $oddRow = !$oddRow;
        
	$xtpl->parse("main.row");
        // Put the rows in.
}

$xtpl->parse("main");
if (count($open_accounts_list)>0) $xtpl->out("main");
else echo "<em>".$current_module_strings['NTC_NONE_SCHEDULED']."</em>";
echo "<BR>";
// Stick on the form footer
echo get_form_footer();

?>
