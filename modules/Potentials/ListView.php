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
require_once('include/utils/utils.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/logging.php');
require_once('include/ListView/ListView.php');
require_once('include/ComboUtil.php');
require_once('include/utils/utils.php');
require_once('include/utils/SearchUtils.php');
require_once('modules/CustomView/CustomView.php');

global $app_strings;
global $app_list_strings;
global $current_language;
$current_module_strings = return_module_language($current_language, 'Potentials');

global $list_max_entries_per_page;
global $urlPrefix;

$log = LoggerManager::getLogger('potential_list');

global $currentModule;
global $theme;

// Get _dom arrays from Database
$comboFieldNames = Array('leadsource'=>'leadsource_dom'
                        ,'opportunity_type'=>'opportunity_type_dom'
                        ,'sales_stage'=>'sales_stage_dom');
$comboFieldArray = getComboArray($comboFieldNames);

$category = getParentTab();

if (!isset($where)) $where = "";

$url_string = ''; // assigning http url string

$focus = new Potential();

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
	if (isset($_REQUEST['potentialname'])) $name = $_REQUEST['potentialname'];
	if (isset($_REQUEST['accountname'])) $accountname = $_REQUEST['accountname'];
	if (isset($_REQUEST['closingdate'])) $date_closed = $_REQUEST['closingdate'];
	if (isset($_REQUEST['amount'])) $amount = $_REQUEST['amount'];
	if (isset($_REQUEST['nextstep'])) $nextstep = $_REQUEST['nextstep'];
	if (isset($_REQUEST['probability'])) $probability = $_REQUEST['probability'];

	if (isset($_REQUEST['leadsource'])) $lead_source = $_REQUEST['leadsource'];
	if (isset($_REQUEST['opportunity_type'])) $opportunity_type = $_REQUEST['opportunity_type'];
	if (isset($_REQUEST['sales_stage'])) $sales_stage = $_REQUEST['sales_stage'];
	if (isset($_REQUEST['current_user_only'])) $current_user_only = $_REQUEST['current_user_only'];
	if (isset($_REQUEST['assigned_user_id'])) $assigned_user_id = $_REQUEST['assigned_user_id'];
	if (isset($_REQUEST['closingdate_start'])) $date_closed_start = $_REQUEST['closingdate_start'];
	if (isset($_REQUEST['closingdate_end'])) $date_closed_end = $_REQUEST['closingdate_end'];

	$where_clauses = array();

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
//upto this added for Custom Field

	if(isset($name) && $name != "") {
			array_push($where_clauses, "potential.potentialname like ".PearDatabase::quote($name.'%')."");
			$url_string .= "&potentialname=".$name;
	}
	if(isset($accountname) && $accountname != "") {
			array_push($where_clauses, "account.accountname like ".PearDatabase::quote($accountname.'%')."");
			$url_string .= "&accountname=".$accountname;


	}
	if(isset($lead_source) && $lead_source == "None") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$url_string .= "&leadsource=".$lead_source;
	}
	// added to handle request from dashboard GS
	if(isset($lead_source) && $lead_source != "") {
			array_push($where_clauses, "potential.leadsource = ".PearDatabase::quote($lead_source)."");
			$url_string .= "&leadsource=".$lead_source;
	}
	if(isset($opportunity_type) && $opportunity_type != "") {
			array_push($where_clauses, "potential.potentialtype = ".PearDatabase::quote($opportunity_type)."");
			$url_string .= "&opportunity_type=".$opportunity_type;
	}
	if(isset($amount) && $amount != "") {
			array_push($where_clauses, "potential.amount like ".PearDatabase::quote($amount.'%')."");
			$url_string .= "&amount=".$amount;
	}
	if(isset($nextstep) && $nextstep != "") {
			array_push($where_clauses, "potential.nextstep like ".PearDatabase::quote($nextstep.'%')."");
			$url_string .= "&nextstep=".$nextstep;
	}
	if(isset($sales_stage) && $sales_stage != "") {
			if($sales_stage=='Other')
				array_push($where_clauses, "(potential.sales_stage <> 'Closed Won' and potential.sales_stage <> 'Closed Lost')");
			else
				array_push($where_clauses, "potential.sales_stage = ".PearDatabase::quote($sales_stage));
			$url_string .= "&sales_stage=".$sales_stage;
	}
	if(isset($probability) && $probability != "") {
			array_push($where_clauses, "potential.probability like ".PearDatabase::quote($probability.'%')."");
			$url_string .= "&probability=".$probability;

	}
	if(isset($current_user_only) && $current_user_only != "") {
			array_push($where_clauses, "crmentity.smownerid='$current_user->id'");
			$url_string .= "&current_user_only=".$current_user_only;
	}
	if(isset($date_closed) && $date_closed != "") {
			array_push($where_clauses, $adb->getDBDateString("potential.closingdate")." like ".PearDatabase::quote($date_closed.'%')."");
			$url_string .= "&closingdate=".$date_closed;
	}
	if(isset($date_closed_start) && $date_closed_start != "" && isset($date_closed_end) && $date_closed_end != "")
	{
			array_push($where_clauses, "potential.closingdate >= ".PearDatabase::quote($date_closed_start)." and potential.closingdate <= ".PearDatabase::quote($date_closed_end));
			$url_string .= "&closingdate_start=".$date_closed_start;
	}

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
			$where .= "".PearDatabase::quote($val)."";
			$where .= ($key == count($assigned_user_id) - 1) ? ")" : ", ";
			// ACIPIA - to allow prev/next button to use criterias
			$url_string .= '&' . urlencode( 'assigned_user_id[]' ) . '=' . $val ;
			// ACIPIA 
		}
	}

	$log->info("Here is the where clause for the list view: $where");

}

//<<<<cutomview>>>>>>>
$oCustomView = new CustomView("Potentials");
$customviewcombo_html = $oCustomView->getCustomViewCombo();
$viewid = $oCustomView->getViewId($currentModule);
$viewnamedesc = $oCustomView->getCustomViewByCvid($viewid);
//<<<<<customview>>>>>


if(isPermitted('Potentials',2,'') == 'yes')
{
        $other_text ='<td><input class="button" type="submit" value="'.$app_strings[LBL_MASS_DELETE].'" onclick="return massDelete()"/></td>';
}

if($viewnamedesc['viewname'] == 'All')
{
$cvHTML = '<span class="bodyText disabled">'.$app_strings['LNK_CV_EDIT'].'</span>
<span class="sep">|</span>
<span class="bodyText disabled">'.$app_strings['LNK_CV_DELETE'].'</span><span class="sep">|</span>
<a href="index.php?module=Potentials&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}else
{
$cvHTML = '<a href="index.php?module=Potentials&action=CustomView&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_EDIT'].'</a>
<span class="sep">|</span>
<a href="index.php?module=CustomView&action=Delete&dmodule=Potentials&record='.$viewid.'" class="link">'.$app_strings['LNK_CV_DELETE'].'</a>
<span class="sep">|</span>
<a href="index.php?module=Potentials&action=CustomView" class="link">'.$app_strings['LNK_CV_CREATEVIEW'].'</a>';
}

$customstrings ='<td align="right">'.$app_strings[LBL_VIEW].'
			<SELECT NAME="viewname" onchange="showDefaultCustomView(this)">
				'.$customviewcombo_html.'
			</SELECT>
			'.$cvHTML.'
		</td>';


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

$smarty = new vtigerCRM_Smarty();
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("CUSTOMVIEW", $customstrings);
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
$smarty->assign("NAVIGATION", $navigationOutput);
$smarty->assign("RECORD_COUNTS", $record_string);
$smarty->assign("SELECT_SCRIPT", $view_script);


$smarty->display("ListView.tpl");


?>
