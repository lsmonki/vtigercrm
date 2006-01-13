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
function getTopSalesOrder()
{
	require_once("data/Tracker.php");
	require_once('modules/SalesOrder/SalesOrder.php');
	require_once('include/logging.php');
	require_once('include/ListView/ListView.php');
	require_once('include/database/PearDatabase.php');
	require_once('include/ComboUtil.php');
	require_once('include/utils/utils.php');
	require_once('modules/CustomView/CustomView.php');

	global $app_strings;
	global $current_language;
	global $current_user;
	$current_module_strings = return_module_language($current_language, 'SalesOrder');

	global $list_max_entries_per_page;
	global $urlPrefix;

	$log = LoggerManager::getLogger('so_list');

	global $currentModule;
	global $theme;
	global $adb;

	// focus_list is the means of passing data to a ListView.
	global $focus_list;

	$url_string = '';
	$sorder = '';
	$oCustomView = new CustomView("SalesOrder");
	$customviewcombo_html = $oCustomView->getCustomViewCombo();
	if(isset($_REQUEST['viewname']) == false || $_REQUEST['viewname']=='')
	{
		if($oCustomView->setdefaultviewid != "")
		{
			$viewid = $oCustomView->setdefaultviewid;
		}else
		{
			$viewid = "0";
		}
	}
	$focus = new SalesOrder();

	global $theme;
	$theme_path="themes/".$theme."/";
	$image_path=$theme_path."images/";

	//Retreive the list from Database
	//<<<<<<<<<customview>>>>>>>>>
	$date_var = date('Y-m-d');

	$where = ' and crmentity.smownerid='.$current_user->id.' and  salesorder.duedate >= \''.$date_var.'\' ORDER BY total DESC';
	$query = getListQuery("SalesOrder",$where);

	//<<<<<<<<customview>>>>>>>>>

	$list_result = $adb->limitQuery($query,0,5);

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


	//Retreive the List View Table Header
	$title=array('myTopSalesOrders.gif',$current_module_strings['LBL_MY_TOP_SO'],'home_mytopso');
	$listview_header = getListViewHeader($focus,"SalesOrder",$url_string,$sorder,$order_by,"HomePage",$oCustomView);

	$listview_entries = getListViewEntries($focus,"SalesOrder",$list_result,$navigation_array,"HomePage","","EditView","Delete",$oCustomView);
	$values=Array('Title'=>$title,'Header'=>$listview_header,'Entries'=>$listview_entries);
	return $values;
}
?>
