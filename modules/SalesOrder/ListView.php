<?
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
require_once('include/database/PearDatabase.php');
require_once('Smarty_setup.php');
require_once('modules/SalesOrder/SalesOrder.php');
require_once('include/utils/utils.php');
require_once('include/utils/utils.php');
require_once('modules/CustomView/CustomView.php');


global $app_strings;
global $mod_strings;
global $list_max_entries_per_page;

global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MODULE",$currentModule);
$smarty->assign("SINGLE_MOD",'SalesOrder');
$category = getParentTab();
$smarty->assign("CATEGORY",$category);

$focus = new SalesOrder();
$other_text = Array();
$url_string = ''; // assigning http url string

//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>
if($_REQUEST['order_by'] != '')
	$order_by = $_REQUEST['order_by'];
else
	$order_by = (($_SESSION['SALESORDER_ORDER_BY'] != '')?($_SESSION['SALESORDER_ORDER_BY']):($focus->default_order_by));

if($_REQUEST['sorder'] != '')
	$sorder = $_REQUEST['sorder'];
else
	$sorder = (($_SESSION['SALESORDER_SORT_ORDER'] != '')?($_SESSION['SALESORDER_SORT_ORDER']):($focus->default_sort_order));

$_SESSION['SALESORDER_ORDER_BY'] = $order_by;
$_SESSION['SALESORDER_SORT_ORDER'] = $sorder;
//<<<<<<<<<<<<<<<<<<< sorting - stored in session >>>>>>>>>>>>>>>>>>>>


if(isset($_REQUEST['query']) && $_REQUEST['query'] != '' && $_REQUEST['query'] == 'true')
{
	$where=Search($currentModule);
	
	$url_string .="&query=true";
	if (isset($_REQUEST['subject'])) $subject = $_REQUEST['subject'];
        if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
        if (isset($_REQUEST['quotename'])) $quotename = $_REQUEST['quotename'];

	$where_clauses = Array();

	//Added for Custom Field Search
	$sql="select * from field where tablename='salesordercf' order by fieldlabel";
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
                                $str=" salesordercf.".$column[$i]." = 1";
                        else
			        $str=" salesordercf.".$column[$i]." like '$customfield[$i]%'";
		        array_push($where_clauses, $str);
			$url_string .="&".$column[$i]."=".$customfield[$i];
	        }
	}
	//upto this added for Custom Field

	foreach($where_clauses as $clause)
	{
		if($where != "")
		$where .= " and ";
		$where .= $clause;
	}

	$log->info("Here is the where clause for the list view: $where");
 

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("SalesOrder");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>

// Buttons and View options
if(isPermitted('SalesOrder',2,'') == 'yes')
{
	$other_text['del'] = $app_strings[LBL_MASS_DELETE];
}

if($viewnamedesc['viewname'] == 'All')
{
$cvHTML = '<td><a href="index.php?module=SalesOrder&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<span class="small" disabled>'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="small">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span></td>';
}else
{
$cvHTML = '<td><a href="index.php?module=SalesOrder&action=CustomView">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>
<span class="small">|</span>
<a href="index.php?module=SalesOrder&action=CustomView&record='.$viewid.'">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="small">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=SalesOrder&record='.$viewid.'">'.$app_strings['LNK_CV_DELETE'].'</a></td>';
}
	$customstrings = '<td align="right" class="small">'.$app_strings[LBL_VIEW].'</td>
			<td><SELECT NAME="viewname" class="small" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
                        </SELECT></td>
			'.$cvHTML;

//<<<<<<<<<customview>>>>>>>>>
if($viewid != "0")
{
	$listquery = getListQuery("SalesOrder");
	$list_query = $oCustomView->getModifiedCvListQuery($viewid,$listquery,"SalesOrder");
}else
{
	$list_query = getListQuery("SalesOrder");
}
//<<<<<<<<customview>>>>>>>>>

if(isset($where) && $where != '')
{
        $list_query .= ' and '.$where;
}

$smarty->assign("SOLISTHEADER", get_form_header($current_module_strings['LBL_LIST_SO_FORM_TITLE'], $other_text, false ));

if(isset($order_by) && $order_by != '')
{
	if($order_by == 'smownerid')
        {
                $list_query .= ' ORDER BY user_name '.$sorder;
        }
        else
        {
		$tablename = getTableNameForField('SalesOrder',$order_by);
		$tablename = (($tablename != '')?($tablename."."):'');

                $list_query .= ' ORDER BY '.$tablename.$order_by.' '.$sorder;
        }
}

$list_result = $adb->query($list_query);


//Retreiving the no of rows
$noofrows = $adb->num_rows($list_result);

$view_script = "<script language='javascript'>
	function set_selected()
	{
		len=document.massdelete.viewname.length;
		for(i=0;i<len;i++)
		{
			if(document.massdelete.viewname[i].value == '$viewid')
				document.massdelete.viewname[i].selected = true;
		}
	}
	set_selected();
	</script>";

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

$listview_header = getListViewHeader($focus,"SalesOrder",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("LISTHEADER", $listview_header);

$listview_header_search=getSearchListHeaderValues($focus,"SalesOrder",$url_string,$sorder,$order_by,"",$oCustomView);
$smarty->assign("SEARCHLISTHEADER", $listview_header_search);

$listview_entries = getListViewEntries($focus,"SalesOrder",$list_result,$navigation_array,'','&return_module=SalesOrder&return_action=index','EditView','Delete',$oCustomView);
$smarty->assign("LISTENTITY", $listview_entries);
$smarty->assign("SELECT_SCRIPT", $view_script);

$navigationOutput = getTableHeaderNavigation($navigation_array, $url_string,"SalesOrder",'index',$viewid);
$alphabetical = AlphabeticalSearch($currentModule,'index','subject','true','basic',"","","","",$viewid);
$smarty->assign("ALPHABETICAL", $alphabetical);
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("CUSTOMVIEW", $customstrings);
$smarty->assign("BUTTONS", $other_text);
$smarty->display("ListView.tpl");

?>
