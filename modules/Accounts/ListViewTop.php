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
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
function getTopAccounts()
{
	require_once("data/Tracker.php");
	require_once('modules/Potentials/Opportunity.php');
	require_once('include/logging.php');
	require_once('include/ListView/ListView.php');
	global $app_strings;
	global $adb;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, "Accounts");
	$log = LoggerManager::getLogger('top accounts_list');

	$list_query = 'select account.accountid, account.accountname, account.tickersymbol, sum(potential.amount) as amount from potential inner join crmentity on (potential.potentialid=crmentity.crmid) inner join account on (potential.accountid=account.accountid) where crmentity.deleted=0 AND crmentity.smownerid="'.$current_user->id.'" and potential.sales_stage <> "'.$app_strings['LBL_CLOSE_WON'].'" and potential.sales_stage <> "'.$app_strings['LBL_CLOSE_LOST'].'" group by account.accountname order by 3 desc;';
	$list_result=$adb->query($list_query);
	$open_accounts_list = array();
	$noofrows = min($adb->num_rows($list_result),7);
	if (count($list_result)>0)
		for($i=0;$i<$noofrows;$i++) 
		{
			$open_accounts_list[] = Array('accountid' => $adb->query_result($list_result,$i,'accountid'),
					'accountname' => $adb->query_result($list_result,$i,'accountname'),
					'amount' => $adb->query_result($list_result,$i,'amount'),
					'tickersymbol' => $adb->query_result($list_result,$i,'tickersymbol'),
					);								 
		}

	$title=array();
	$title[]='myTopAccounts.gif';
	$title[]=$current_module_strings['LBL_TOP_ACCOUNTS'];
	$title[]='home_myaccount';
	
	$header=array();
	$header[]=$current_module_strings['LBL_LIST_ACCOUNT_NAME'];
	$currencyid=fetchCurrency($current_user->id);
        $curr_symbol=getCurrencySymbol($currencyid);
        $rate = getConversionRate($currencyid,$curr_symbol);
        $header[]=$current_module_strings['LBL_LIST_AMOUNT'].'('.$curr_symbol.')';
	
	$entries=array();
	foreach($open_accounts_list as $account)
	{
		$value=array();
		$account_fields = array(
				'ACCOUNT_ID' => $account['accountid'],
				'ACCOUNT_NAME' => $account['accountname'],
				'AMOUNT' => ($account['amount']),
				);

		$value[]='<a href="index.php?action=DetailView&module=Accounts&record='.$account['accountid'].'" onMouseOver=getHeadLines("'.$account['tickersymbol'].'")>'.$account['accountname'].'</a>';
		$value[]=convertFromDollar($account['amount'],$rate);
		$entries[$account['accountid']]=$value;	
	}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);
	if ( ($display_empty_home_blocks && count($open_accounts_list) == 0 ) || (count($open_accounts_list)>0) )
		return $values;
}
?>
