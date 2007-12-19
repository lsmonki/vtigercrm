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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Potentials/ListViewTop.php,v 1.18 2005/04/20 20:24:30 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

/**Function to get the top 5 Potentials order by Amount in Descending Order
 *return array $values - array with the title, header and entries like  Array('Title'=>$title,'Header'=>$listview_header,'Entries'=>$listview_entries) where as listview_header and listview_entries are arrays of header and entity values which are returned from function getListViewHeader and getListViewEntries
*/
function getTopPotentials()
{
	$log = LoggerManager::getLogger('top opportunity_list');
	$log->debug("Entering getTopPotentials() method ...");
	require_once("data/Tracker.php");
	require_once('modules/Potentials/Potentials.php');
	require_once('include/logging.php');
	require_once('include/ListView/ListView.php');

	global $app_strings;
	global $adb;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, "Potentials");

	$title=array();
	$title[]='myTopOpenPotentials.gif';
	$title[]=$current_module_strings['LBL_TOP_OPPORTUNITIES'];
	$title[]='home_mypot';
	$where = "AND vtiger_potential.sales_stage not in ('Closed Won','Closed Lost','".$current_module_strings['Closed Won']."','".$current_module_strings['Closed Lost']."') AND vtiger_crmentity.smownerid='".$current_user->id."'";
	$header=array();
	$header[]=$current_module_strings['LBL_LIST_OPPORTUNITY_NAME'];
	$header[]=$current_module_strings['LBL_LIST_ACCOUNT_NAME'];
	$currencyid=fetchCurrency($current_user->id);
	$rate_symbol = getCurrencySymbolandCRate($currencyid);
	$rate = $rate_symbol['rate'];
	$curr_symbol = $rate_symbol['symbol'];
        $header[]=$current_module_strings['LBL_LIST_AMOUNT'].'('.$curr_symbol.')';
	$header[]=$current_module_strings['LBL_LIST_DATE_CLOSED'];
	$list_query = getListQuery("Potentials",$where);
	$list_query .=" ORDER BY amount DESC";
	$list_result = $adb->limitQuery($list_query,0,5);
	$open_potentials_list = array();
	$noofrows = $adb->num_rows($list_result);
	$entries=array();
	if (count($list_result)>0)
		for($i=0;$i<$noofrows;$i++) 
		{
			$open_potentials_list[] = Array('name' => $adb->query_result($list_result,$i,'potentialname'),
					'id' => $adb->query_result($list_result,$i,'potentialid'),
					'accountid' => $adb->query_result($list_result,$i,'accountid'),
					'accountname' => $adb->query_result($list_result,$i,'accountname'),
					'amount' => $adb->query_result($list_result,$i,'amount'),
					'closingdate' => getDisplayDate($adb->query_result($list_result,$i,'closingdate')),
					);
			$potentialid=$adb->query_result($list_result,$i,'potentialid');                                  
			$potentialname = $adb->query_result($list_result,$i,'potentialname');
			$Top_Potential = (strlen($potentialname) > 20) ? (substr($potentialname,0,20).'...') : $potentialname;
			$value=array();
			$value[]='<a href="index.php?action=DetailView&module=Potentials&record='.$potentialid.'">'.$Top_Potential.'</a>';
			$value[]='<a href="index.php?action=DetailView&module=Accounts&record='.$adb->query_result($list_result,$i,'accountid').'">'.$adb->query_result($list_result,$i,"accountname").'</a>';
			$value[]=convertFromDollar($adb->query_result($list_result,$i,'amount'),$rate);
			$value[]=getDisplayDate($adb->query_result($list_result,$i,'closingdate'));
			$entries[$potentialid]=$value;
		}
	$values=Array('Title'=>$title,'Header'=>$header,'Entries'=>$entries);

	if ( ($display_empty_home_blocks && count($open_potentials_list) == 0 ) || (count($open_potentials_list)>0) )
	{
		$log->debug("Exiting getTopPotentials method ...");
		return $values;		
	}
}
?>
