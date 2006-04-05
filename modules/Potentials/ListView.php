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

require_once('Smarty_setup.php');
require_once("data/Tracker.php");
require_once('modules/Potentials/Opportunity.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings,$list_max_entries_per_page;

$log = LoggerManager::getLogger('potential_list');

global $currentModule,$theme;

$category = getParentTab();

if (!isset($where)) $where = "";

$url_string = ''; // assigning http url string

$focus = new Potential();
$smarty = new vtigerCRM_Smarty();
$other_text = Array();

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['POTENTIALS_ORDER_BY'] != '')?($_SESSION['POTENTIALS_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['POTENTIALS_SORT_ORDER'] != '')?($_SESSION['POTENTIALS_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['POTENTIALS_ORDER_BY'] = $order_by;
$_SESSION['POTENTIALS_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>

if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'true')
{
	// we have a query
	$url_string .="&query=true";

	//Call for search function - Jaguar
	$where=Search($currentModule);


	//Added for Custom Field Search
	$sql="select * from field where tablename='potentialscf' order by fieldlabel";
	$result=$adb->query($sql);
	for($i=0;$i<$adb->num_rows($result);$i++)
	{
		$column[$i]=$adb->query_result($result,$i,'columnname');
		$fieldlabel[$i]=$adb->query_result($result,$i,'fieldlabel');
		$uitype[$i]=$adb->query_result($result,$i,'uitype');
		if (isset($_REQUEST[$column[$i]])) $customfield[$i] = $_REQUEST[$column[$i]];

		if(isset($customfield[$i]) && $customfield[$i] != '')
		{
			if($uitype[$i] == 56)
				$str = " potentialscf.".$column[$i]." = 1";
			elseif($uitype[$i] == 15)//Added to handle the picklist customfield - after 4.2 patch2
				$str = " potentialscf.".$column[$i]." = '".$customfield[$i]."'";
			else
				$str = " potentialscf.".$column[$i]." like '$customfield[$i]%'";
			array_push($where_clauses, $str);
			$url_string .="&".$column[$i]."=".$customfield[$i];
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Potentials");
$viewid = $oCustomView->getViewId($currentModule);
$customviewcombo_html = $oCustomView->getCustomViewCombo($viewid);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>


if(isPermitted('Potentials',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
	$smarty->assign("ALL", 'All');
}

//Retreive the list from Database
//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("Potentials");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"Accounts");
}else
{
	$list_query = getListQuery("Potentials");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
	$list_query .= " AND ".$where;
}

if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
        {
                $list_query .= ' ORDER BY user_name '.$sorder;
        }
        else
        {
		$tablename = getTableNameForField('Potentials',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

                $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
        }

}

$list_result = $adb->query($list_query);

//Constructing the list view

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW_OPTION",$customviewcombo_html);
$smarty->assign("VIEWID", $viewid);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'Opportunity');
$smarty->assign("BUTTONS",$other_text);
$smarty->assign("CATEGORY",$category);


//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

//Retreiving the start value from request
if(isset($_REQUEST['start']) && $_REQUEST['start'] != '')
{
	$start = $_REQUEST['start'];

	//added to remain the navigation when sort
	$url_string = "&start=".$_REQUEST['start'];
}
else
{
	$start = 1;
}
//Retreive the Navigation array
$navigation_array = getNavigationValues($start, $noofrows, $list_max_entries_per_page);


// Setting the record count string
//modified by rdhital
$start_rec = $navigation_array['start'];
$end_rec = $navigation_array['end_val']; 
//By Raju Ends

$record_string= $app_strings[LBL_SHOWING]." " .$start_rec." - ".$end_rec." " .$app_strings[LBL_LIST_OF] ." ".$noofrows;

//Retreive the List View Table Header
if($viewid !='')
$url_string .="&viewname=".$viewid;

$listview_header = getListViewHeader($focus,"Potentials",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"Potentials",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);


$listview_entries = getListViewEntries($focus,"Potentials",$list_result,$navigation_array,"","","EditView","Delete",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);
$smarty->assign("LISTENTITY", $listview_entries);


$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"Potentials","index",$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','potentialname','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("SELECT_SCRIPT", $view_script);



if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '')
	$smarty->display("ListViewEntries.tpl");
else	
	$smarty->display("ListView.tpl");

?>
